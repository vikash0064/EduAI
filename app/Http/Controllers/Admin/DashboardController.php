<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'total_students' => StudentProfile::count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'outstanding_fees' => \App\Models\Fee::where('status', 'unpaid')->sum('amount'),
            'total_announcements' => \App\Models\Announcement::count(),
        ];

        // Academic Health Calculation (Avg of all grades / max_score)
        $avgScore = \App\Models\Grade::avg('score') ?? 0;
        $maxScore = \App\Models\Grade::avg('max_score') ?? 100;
        $stats['academic_health'] = $maxScore > 0 ? round(($avgScore / $maxScore) * 100, 1) : 0;

        // Department Metrics (Subject Averages)
        $departmentMetrics = \App\Models\Subject::withAvg('grades', 'score')
            ->take(3)
            ->get()
            ->map(function($subject) {
                $avg = $subject->grades_avg_score ?? 0;
                $grade = $avg >= 90 ? 'A+' : ($avg >= 80 ? 'A' : ($avg >= 70 ? 'B+' : 'B'));
                return [
                    'name' => $subject->name,
                    'grade' => $grade,
                    'avg' => round($avg, 1)
                ];
            });

        // Enrollment Trends
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyTrends[] = [
                'label' => $date->format('M'),
                'count' => StudentProfile::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count()
            ];
        }

        $yearlyTrends = [];
        for ($i = 4; $i >= 0; $i--) {
            $year = now()->subYears($i)->year;
            $yearlyTrends[] = [
                'label' => (string)$year,
                'count' => StudentProfile::whereYear('created_at', $year)->count()
            ];
        }

        $stats['enrollment_trends'] = [
            'monthly' => $monthlyTrends,
            'yearly' => $yearlyTrends
        ];

        // Faculty Data
        $faculty = User::where('role', 'teacher')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($teacher) {
                // Mocking utilization for now as we don't have workload tracking
                return [
                    'name' => $teacher->name,
                    'initials' => strtoupper(substr($teacher->name, 0, 1) . substr(explode(' ', $teacher->name)[1] ?? '', 0, 1)),
                    'department' => 'Academic',
                    'utilization' => rand(75, 98),
                    'status' => 'Active'
                ];
            });

        $recentFees = \App\Models\Fee::with('studentProfile.user')->latest()->take(5)->get();
        $upcomingEvents = \App\Models\Activity::where('event_date', '>=', now())->orderBy('event_date')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentFees', 'upcomingEvents', 'departmentMetrics', 'faculty'));
    }
}
