<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $parent = Auth::user()->parentProfile;
        
        if (!$parent) {
            return redirect()->route('dashboard')->with('error', 'Parent profile not found.');
        }

        $children = $parent->students()->with(['user'])->get();
        
        if ($children->isEmpty()) {
            return view('parent.dashboard', compact('children'));
        }

        // Default to first child or requested child
        $selectedChildId = $request->get('child_id', $children->first()->id);
        $selectedStudent = $children->where('id', $selectedChildId)->first() ?? $children->first();
        
        $selectedStudent->load(['user', 'attendances', 'grades.subject', 'fees']);

        // Stats
        $attendanceCount = $selectedStudent->attendances->count();
        $presentCount = $selectedStudent->attendances->where('status', 'present')->count();
        $attendancePercentage = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100) : 0;

        $gpa = $selectedStudent->grades->count() > 0 ? number_format($selectedStudent->grades->avg('score') / 10, 1) : 'N/A';

        $upcomingExams = \App\Models\Exam::where('grade_level', $selectedStudent->grade_level)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(3)
            ->get();

        $recentGrades = $selectedStudent->grades()->with('subject')->latest()->take(5)->get();
        $pendingFees = $selectedStudent->fees()->where('status', 'unpaid')->sum('amount');

        return view('parent.dashboard', compact(
            'children',
            'selectedStudent',
            'attendancePercentage',
            'gpa',
            'upcomingExams',
            'recentGrades',
            'pendingFees'
        ));
    }

    public function showChild(StudentProfile $student)
    {
        $this->authorizeChild($student);

        $student->load(['user', 'attendances', 'grades.subject', 'fees']);

        // Stats
        $attendanceCount = $student->attendances->count();
        $presentCount = $student->attendances->where('status', 'present')->count();
        $attendancePercentage = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100) : 0;

        $gpa = $student->grades->avg('score') / 10; // Simple GPA calc for demo

        $upcomingExams = \App\Models\Exam::where('grade_level', $student->grade_level)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->take(3)
            ->get();

        $recentGrades = $student->grades()->with('subject')->latest()->take(5)->get();
        $pendingFees = $student->fees()->where('status', 'unpaid')->sum('amount');

        return view('parent.child_dashboard', compact(
            'student', 
            'attendancePercentage', 
            'gpa', 
            'upcomingExams', 
            'recentGrades',
            'pendingFees'
        ));
    }

    public function attendance(StudentProfile $student)
    {
        $this->authorizeChild($student);
        $attendances = $student->attendances()->orderBy('date', 'desc')->paginate(15);
        
        return view('parent.attendance', compact('student', 'attendances'));
    }

    public function results(StudentProfile $student)
    {
        $this->authorizeChild($student);
        $results = $student->grades()->with('subject')->orderBy('created_at', 'desc')->get();
        
        return view('parent.results', compact('student', 'results'));
    }

    public function fees(StudentProfile $student)
    {
        $this->authorizeChild($student);
        $fees = $student->fees()->orderBy('due_date', 'desc')->get();
        
        return view('parent.fees', compact('student', 'fees'));
    }

    private function authorizeChild(StudentProfile $student)
    {
        $parent = Auth::user()->parentProfile;
        if (!$parent || !$parent->students->contains($student->id)) {
            abort(403, 'Unauthorized access to student data.');
        }
    }
}
