<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $dayOfWeek = date('l', strtotime($date));
        
        // Extended CSE Timetable (9 AM - 5 PM)
        $timetable = [
            'Monday' => [
                ['time' => '09:00 AM - 10:30 AM', 'class' => 'CSE-4A', 'subject' => 'Data Structures', 'room' => 'Lab 101'],
                ['time' => '10:45 AM - 12:15 PM', 'class' => 'CSE-4B', 'subject' => 'Operating Systems', 'room' => 'LT-2'],
                ['time' => '01:30 PM - 03:00 PM', 'class' => 'CSE-3A', 'subject' => 'Database Systems', 'room' => 'Lab 204'],
                ['time' => '03:15 PM - 04:45 PM', 'class' => 'CSE-2C', 'subject' => 'Discrete Math', 'room' => 'Room 12'],
            ],
            'Tuesday' => [
                ['time' => '09:00 AM - 10:30 AM', 'class' => 'CSE-4C', 'subject' => 'Computer Networks', 'room' => 'Lab 302'],
                ['time' => '11:00 AM - 12:30 PM', 'class' => 'CSE-3B', 'subject' => 'Theory of Computation', 'room' => 'LT-1'],
                ['time' => '02:00 PM - 03:30 PM', 'class' => 'CSE-4A', 'subject' => 'Software Engineering', 'room' => 'Room 15'],
                ['time' => '03:45 PM - 05:00 PM', 'class' => 'CSE-2A', 'subject' => 'Object Oriented Programming', 'room' => 'Lab 104'],
            ],
            'Wednesday' => [
                ['time' => '09:00 AM - 11:00 AM', 'class' => 'CSE-4B', 'subject' => 'Machine Learning', 'room' => 'AI Lab'],
                ['time' => '11:15 AM - 12:45 PM', 'class' => 'CSE-3A', 'subject' => 'Microprocessors', 'room' => 'Hardware Lab'],
                ['time' => '02:00 PM - 03:30 PM', 'class' => 'CSE-4C', 'subject' => 'Web Technologies', 'room' => 'Web Lab'],
                ['time' => '03:45 PM - 05:00 PM', 'class' => 'CSE-2B', 'subject' => 'Digital Logic', 'room' => 'LT-3'],
            ],
            'Thursday' => [
                ['time' => '09:00 AM - 10:30 AM', 'class' => 'CSE-3C', 'subject' => 'Computer Graphics', 'room' => 'Graphics Lab'],
                ['time' => '10:45 AM - 12:15 PM', 'class' => 'CSE-4A', 'subject' => 'Compiler Design', 'room' => 'LT-2'],
                ['time' => '01:30 PM - 03:00 PM', 'class' => 'CSE-3B', 'subject' => 'Cloud Computing', 'room' => 'Cloud Lab'],
                ['time' => '03:15 PM - 04:45 PM', 'class' => 'CSE-4B', 'subject' => 'Cyber Security', 'room' => 'Security Lab'],
            ],
            'Friday' => [
                ['time' => '09:00 AM - 11:00 AM', 'class' => 'CSE-4C', 'subject' => 'Artificial Intelligence', 'room' => 'AI Lab'],
                ['time' => '11:15 AM - 12:45 PM', 'class' => 'CSE-2A', 'subject' => 'Ethics in IT', 'room' => 'Seminar Hall'],
                ['time' => '02:00 PM - 05:00 PM', 'class' => 'CSE-4B', 'subject' => 'Capstone Project Lab', 'room' => 'Innovation Lab'],
            ],
            'Saturday' => [
                ['time' => '09:00 AM - 12:00 PM', 'class' => 'CSE-All', 'subject' => 'Industry Expert Session', 'room' => 'Main Auditorium'],
                ['time' => '01:00 PM - 04:00 PM', 'class' => 'CSE-Staff', 'subject' => 'Research & Development', 'room' => 'Faculty Room'],
            ],
        ];

        // Determine current session
        $currentTime = date('H:i');
        $currentSession = null;
        $todaySchedule = $timetable[$dayOfWeek] ?? [];

        foreach ($todaySchedule as $session) {
            list($start, $end) = explode(' - ', $session['time']);
            $startTime = date('H:i', strtotime($start));
            $endTime = date('H:i', strtotime($end));
            
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $currentSession = $session;
                break;
            }
        }

        $targetClass = $request->class ?? ($currentSession['class'] ?? ($todaySchedule[0]['class'] ?? null));

        $query = StudentProfile::with(['user', 'attendances' => function($q) use ($date) {
            $q->where('date', $date);
        }]);

        if ($targetClass && $targetClass !== 'All Classes') {
            if (str_contains($targetClass, '-')) {
                list($grade, $section) = explode('-', $targetClass);
                $query->where('grade_level', $grade)->where('section', $section);
            } else {
                $query->where('grade_level', $targetClass);
            }
        }

        $students = $query->get();
        $classStrength = $students->count();

        return view('attendance.index', compact('students', 'date', 'timetable', 'todaySchedule', 'currentSession', 'targetClass', 'classStrength'));
    }

    public function markAjax(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:student_profiles,id',
            'status' => 'required|in:present,absent,late',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::updateOrCreate(
            ['student_profile_id' => $validated['student_id'], 'date' => $validated['date']],
            ['status' => $validated['status']]
        );

        // Notify Student if Absent or Late
        if (in_array($validated['status'], ['absent', 'late'])) {
            $student = StudentProfile::find($validated['student_id']);
            $student->user->notify(new \App\Notifications\GeneralNotification(
                'Attendance Update',
                "You were marked as " . ucfirst($validated['status']) . " for today.",
                'alert',
                'event_busy'
            ));
        }

        return response()->json(['success' => true, 'status' => $attendance->status]);
    }

    public function myAttendance()
    {
        $profile = Auth::user()->studentProfile;
        if(!$profile) return redirect()->route('dashboard');
        
        $attendances = $profile->attendances()->orderBy('date', 'desc')->get();
        
        // Calculate Stats
        $stats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'total' => $attendances->count(),
        ];

        $percentage = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100) : 0;

        // Prepare Chart Data (Last 7 marks)
        $chartData = $attendances->take(7)->reverse()->map(function($att) {
            return [
                'day' => \Carbon\Carbon::parse($att->date)->format('D'),
                'val' => $att->status === 'present' ? 100 : ($att->status === 'late' ? 50 : 0)
            ];
        });

        return view('attendance.student_index', compact('attendances', 'stats', 'percentage', 'chartData'));
    }
}
