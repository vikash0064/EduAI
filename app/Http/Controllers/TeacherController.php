<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentProfile::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('enrollment_number', 'like', "%{$search}%");
        }

        if ($request->has('grade')) {
            $query->where('grade_level', $request->grade);
        }

        $students = $query->paginate(15);

        // Real Data for Class Performance Flow (Mocking for Mon-Sat)
        // In a real app, this would query attendance/grades average per day
        $performanceData = [
            'weekly' => [85, 88, 76, 92, 89, 82], // Mon to Sat
            'monthly' => [78, 82, 85, 80, 84, 88], // Monthly averages
        ];
        
        return view('teacher.dashboard', compact('students', 'performanceData'));
    }
}
