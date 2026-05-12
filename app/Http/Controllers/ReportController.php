<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function index()
    {
        $reports = \App\Models\Report::with('studentProfile.user')->latest()->take(5)->get();
        
        $counts = [
            'performance' => \App\Models\Report::where('type', 'performance')->count(),
            'attendance' => \App\Models\Report::where('type', 'attendance')->count(),
            'behavioral' => \App\Models\Report::where('type', 'behavioral')->count(),
            'exam' => \App\Models\Report::where('type', 'exam')->count(),
            'total' => \App\Models\Report::count(),
        ];

        return view('reports.index', compact('reports', 'counts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:performance,attendance,behavioral,exam',
        ]);

        \App\Models\Report::create($validated);

        return redirect()->route('admin.reports.index')->with('success', 'Report generation request submitted successfully.');
    }

    public function export(Request $request)
    {
        // Placeholder for PDF/Excel export logic
        // E.g., using DOMPDF or Laravel Excel
        return back()->with('info', 'Export functionality is a placeholder. You can implement dompdf/laravel-excel here.');
    }

    public function emailToParent(Request $request)
    {
        $student = StudentProfile::with('parents')->findOrFail($request->student_profile_id);
        
        foreach ($student->parents as $parent) {
            // Notification logic / Mail logic placeholder
            // Mail::to($parent->email)->send(new ReportMail($student));
            
            // Just creating a DB notification for now
            $parent->notifications()->create([
                'title' => 'New Progress Report',
                'message' => 'A new progress report has been generated for ' . $student->user->name,
            ]);
        }

        return back()->with('success', 'Report emailed to parents successfully.');
    }
}
