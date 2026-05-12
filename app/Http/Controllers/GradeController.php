<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        
        // Subject Averages
        $subjectStats = Grade::with('subject')
            ->select('subject_id', DB::raw('AVG(score) as avg_score'))
            ->groupBy('subject_id')
            ->get();

        // Upcoming Exams
        $upcomingExams = Exam::with('subject')->orderBy('date')->take(5)->get();

        // Grade Distribution (Analytics)
        $distribution = [
            'A' => Grade::where('score', '>=', 90)->count(),
            'B' => Grade::whereBetween('score', [75, 89])->count(),
            'C' => Grade::whereBetween('score', [60, 74])->count(),
            'F' => Grade::where('score', '<', 60)->count(),
        ];

        // Top Performers
        $topPerformers = StudentProfile::with('user')
            ->withAvg('grades', 'score')
            ->orderByDesc('grades_avg_score')
            ->take(3)
            ->get();

        // All Grades for the Gradebook Table
        $allGrades = Grade::with(['studentProfile.user', 'subject'])->latest()->get();
        $students = StudentProfile::with('user')->get();

        return view('grades.index', compact(
            'subjects', 'subjectStats', 'upcomingExams', 
            'distribution', 'topPerformers', 'allGrades', 'students'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type' => 'required|string',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
        ]);

        $grade = Grade::create(array_merge($validated, ['grade_date' => now()]));

        // Notify Student
        $grade->studentProfile->user->notify(new \App\Notifications\GeneralNotification(
            'New Result Published',
            "Your result for {$grade->subject->name} ({$grade->exam_type}) is out: {$grade->score}/{$grade->max_score}",
            'info',
            'military_tech'
        ));

        return back()->with('success', 'Grade recorded successfully.');
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'exam_type' => 'required|string',
        ]);

        $grade->update($validated);

        return back()->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return back()->with('success', 'Grade deleted successfully.');
    }

    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'subject_id' => 'required|exists:subjects,id',
            'grade_level' => 'required|string',
            'date' => 'required|date',
            'room' => 'nullable|string',
        ]);

        Exam::create($validated);
        return back()->with('success', 'Exam scheduled successfully.');
    }
    public function myGrades()
    {
        $profile = Auth::user()->studentProfile;
        if(!$profile) return redirect()->route('dashboard');

        $grades = $profile->grades()->with('subject')->latest()->get();
        
        $stats = [
            'avg' => round($grades->avg('score') ?? 0),
            'total' => $grades->count(),
            'highest' => $grades->max('score') ?? 0,
            'lowest' => $grades->min('score') ?? 0,
        ];

        $subjectAverages = $grades->groupBy('subject.name')->map(function($subjectGrades) {
            return round($subjectGrades->avg('score'));
        });

        return view('grades.student_index', compact('grades', 'stats', 'subjectAverages'));
    }
}
