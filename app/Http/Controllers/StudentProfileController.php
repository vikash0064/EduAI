<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentProfileController extends Controller
{
    public function showMyProfile()
    {
        $user = Auth::user();
        $profile = $user->studentProfile;
        
        if (!$profile) {
            return view('students.dashboard', ['profile' => null]);
        }

        // 1. Attendance
        $allAttendances = $profile->attendances;
        $totalPresent = $allAttendances->where('status', 'present')->count();
        $attendancePercentage = $allAttendances->count() > 0 ? round(($totalPresent / $allAttendances->count()) * 100) : 0;

        // 2. GPA
        $avgScore = $profile->grades()->avg('score') ?? 0;
        $gpa = round(($avgScore / 100) * 4.0, 2);

        // 3. Assignments
        $totalAssignments = Assignment::where('grade_level', $profile->grade_level)
            ->where(function($q) use ($profile) {
                $q->where('section', $profile->section)->orWhere('section', 'All');
            })->count();
        $completedAssignments = Submission::where('student_profile_id', $profile->id)->count();

        // 4. Global Rank
        $rankData = StudentProfile::where('grade_level', $profile->grade_level)
            ->withAvg('grades', 'score')
            ->orderByDesc('grades_avg_score')
            ->get();
        $rank = $rankData->search(fn($item) => $item->id === $profile->id);
        $globalRank = ($rank !== false) ? $rank + 1 : $rankData->count();
        $totalStudentsInGrade = $rankData->count();

        // 5. Grouped Grades for the UI (Subject -> Exams)
        $gradesGrouped = $profile->grades()->with('subject')
            ->get()
            ->groupBy('subject.name')
            ->map(function($subjectGrades) {
                return [
                    'avg' => round($subjectGrades->avg('score')),
                    'exams' => $subjectGrades->map(function($g) {
                        return [
                            'type' => $g->exam_type,
                            'score' => $g->score,
                            'max' => $g->max_score,
                            'date' => $g->grade_date ? \Carbon\Carbon::parse($g->grade_date)->format('M d') : 'N/A'
                        ];
                    })
                ];
            });

        // Simplified Mastery for the rings/bars
        $subjectMastery = $gradesGrouped->map(function($data, $name) {
            return ['name' => $name, 'score' => $data['avg'] . '%', 'val' => $data['avg']];
        })->values();

        // 6. Timeline
        $timeline = collect();
        $latestGrades = $profile->grades()->with('subject')->latest()->take(5)->get();
        foreach($latestGrades as $grade) {
            $timeline->push(['type' => 'grade', 'icon' => 'grade', 'title' => 'New Grade Received', 'content' => ($grade->subject->name ?? 'Subject') . ": " . $grade->score . "%", 'time' => $grade->created_at->diffForHumans(), 'color' => 'bg-indigo-500']);
        }
        $latestAtt = $profile->attendances()->latest()->take(5)->get();
        foreach($latestAtt as $att) {
            $timeline->push(['type' => 'attendance', 'icon' => 'calendar_today', 'title' => 'Attendance Marked', 'content' => ucfirst($att->status) . " on " . \Carbon\Carbon::parse($att->date)->format('M d'), 'time' => $att->created_at->diffForHumans(), 'color' => $att->status === 'present' ? 'bg-teal-500' : 'bg-error']);
        }
        $timeline = $timeline->sortByDesc('time')->values();

        $notifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        return view('students.dashboard', compact(
            'profile', 'attendancePercentage', 'gpa', 'completedAssignments', 
            'totalAssignments', 'subjectMastery', 'timeline', 'notifications', 
            'globalRank', 'totalStudentsInGrade', 'gradesGrouped'
        ));
    }

    public function index() { $profiles = StudentProfile::with('user')->get(); return view('students.index', compact('profiles')); }
    
    public function show(StudentProfile $student)
    {
        $user = $student->user;
        $student->load(['attendances', 'grades.subject', 'submissions.assignment', 'fees', 'parents.user']);
        
        // Attendance Stats
        $attendanceCount = $student->attendances->count();
        $presentCount = $student->attendances->where('status', 'present')->count();
        $attendancePercentage = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100) : 0;

        // Grade Stats
        $avgScore = $student->grades->avg('score') ?? 0;
        $gpa = round(($avgScore / 100) * 4.0, 2);

        // Assignment Stats
        $totalAssigned = \App\Models\Assignment::where('grade_level', $student->grade_level)
            ->where(function($q) use ($student) {
                $q->where('section', $student->section)->orWhere('section', 'All');
            })->count();
        $completedCount = $student->submissions->count();

        return view('students.show', compact(
            'student', 
            'attendancePercentage', 
            'gpa', 
            'totalAssigned', 
            'completedCount'
        ));
    }

    public function store(Request $request) { return back(); }
}
