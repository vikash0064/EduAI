<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            $profile = $user->studentProfile;
            if (!$profile) return redirect()->route('dashboard');

            $assignments = Assignment::with(['subject', 'submissions' => function($q) use ($profile) {
                    $q->where('student_profile_id', $profile->id);
                }])
                ->where('grade_level', $profile->grade_level)
                ->where(function($q) use ($profile) {
                    $q->where('section', $profile->section)->orWhere('section', 'All');
                })
                ->latest()
                ->get();

            $completedCount = $assignments->filter(fn($a) => $a->submissions->count() > 0)->count();
            $pendingCount = $assignments->count() - $completedCount;

            return view('assignments.student_index', compact('assignments', 'completedCount', 'pendingCount'));
        }

        $teacherId = $user->id;
        
        // Fetch real data for the dashboard
        $assignments = Assignment::with(['subject', 'submissions'])
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get();

        $totalSubmissions = Submission::whereIn('assignment_id', $assignments->pluck('id'))->count();
        $pendingGrading = Submission::whereIn('assignment_id', $assignments->pluck('id'))
            ->where('status', 'pending')
            ->count();
        
        $avgScore = Submission::whereIn('assignment_id', $assignments->pluck('id'))
            ->where('status', 'graded')
            ->avg('score') ?? 0;

        $subjects = Subject::all();

        return view('assignments.index', compact('assignments', 'totalSubmissions', 'pendingGrading', 'avgScore', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|string',
            'section' => 'required|string',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $assignment = \App\Models\Assignment::create(array_merge($validated, ['teacher_id' => Auth::id(), 'status' => 'active']));

        // Notify Students in this Grade/Section
        $students = \App\Models\StudentProfile::where('grade_level', $validated['grade_level'])
            ->where('section', $validated['section'])
            ->get();
        
        foreach ($students as $student) {
            $student->user->notify(new \App\Notifications\GeneralNotification(
                'New Assignment',
                "New assignment for {$assignment->subject->name}: {$assignment->title}",
                'info',
                'assignment'
            ));
        }

        return back()->with('success', 'Assignment created successfully!');
    }

    public function grade(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
        ]);

        // Automatically create/update a record in the Grades table for the student
        Grade::updateOrCreate(
            [
                'student_profile_id' => $submission->student_profile_id,
                'subject_id' => $submission->assignment->subject_id,
                'exam_type' => 'Assignment: ' . $submission->assignment->title
            ],
            [
                'score' => $validated['score'],
                'max_score' => $submission->assignment->max_score,
                'grade_date' => now(),
            ]
        );

        return back()->with('success', 'Submission graded and recorded.');
    }
}
