<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@demo.com'],
            [
                'name' => 'Professor Xavier',
                'password' => Hash::make('123456'),
                'role' => 'teacher',
            ]
        );

        $parent = User::updateOrCreate(
            ['email' => 'parent@demo.com'],
            [
                'name' => 'Robert Jones',
                'password' => Hash::make('123456'),
                'role' => 'parent',
            ]
        );

        $student1 = User::updateOrCreate(
            ['email' => 'student@demo.com'],
            [
                'name' => 'Student Alice',
                'password' => Hash::make('123456'),
                'role' => 'student',
            ]
        );

        $student2 = User::updateOrCreate(
            ['email' => 'bob@demo.com'],
            [
                'name' => 'Student Bob',
                'password' => Hash::make('123456'),
                'role' => 'student',
            ]
        );

        // 2. Create Profiles
        $parentProfile = \App\Models\ParentProfile::create([
            'user_id' => $parent->id,
            'phone' => '+91 98765 43210',
            'relationship' => 'Father'
        ]);

        $profile1 = StudentProfile::create([
            'user_id' => $student1->id,
            'enrollment_number' => 'STU-1001',
            'grade_level' => 'CSE',
            'section' => '4A',
            'date_of_birth' => '2008-05-15',
        ]);

        $profile2 = StudentProfile::create([
            'user_id' => $student2->id,
            'enrollment_number' => 'STU-1002',
            'grade_level' => 'CSE',
            'section' => '4A',
            'date_of_birth' => '2008-08-22',
        ]);

        // Link Parent to Children
        $parentProfile->students()->attach([$profile1->id, $profile2->id]);

        // 3. Create Subjects
        $math = Subject::create(['name' => 'Mathematics', 'code' => 'MTH101', 'description' => 'Algebra and Geometry']);
        $science = Subject::create(['name' => 'Science', 'code' => 'SCI101', 'description' => 'Physics and Chemistry']);
        $history = Subject::create(['name' => 'History', 'code' => 'HIS101', 'description' => 'World History']);

        // 4. Create Attendance
        $dates = [date('Y-m-d'), date('Y-m-d', strtotime('-1 days')), date('Y-m-d', strtotime('-2 days'))];
        foreach ($dates as $date) {
            Attendance::create(['student_profile_id' => $profile1->id, 'date' => $date, 'status' => 'present']);
            Attendance::create(['student_profile_id' => $profile2->id, 'date' => $date, 'status' => 'present']);
        }

        // 5. Create Grades
        Grade::create(['student_profile_id' => $profile1->id, 'subject_id' => $math->id, 'exam_type' => 'Midterm', 'score' => 85, 'max_score' => 100]);
        Grade::create(['student_profile_id' => $profile1->id, 'subject_id' => $science->id, 'exam_type' => 'Midterm', 'score' => 92, 'max_score' => 100]);
        Grade::create(['student_profile_id' => $profile2->id, 'subject_id' => $math->id, 'exam_type' => 'Midterm', 'score' => 78, 'max_score' => 100]);

        // 6. Create Fees
        \App\Models\Fee::create([
            'student_profile_id' => $profile1->id,
            'title' => 'Term 2 Tuition Fee',
            'amount' => 15000,
            'due_date' => now()->addDays(15),
            'status' => 'unpaid'
        ]);

        \App\Models\Fee::create([
            'student_profile_id' => $profile2->id,
            'title' => 'Term 2 Tuition Fee',
            'amount' => 15000,
            'due_date' => now()->addDays(15),
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_id' => 'TXN_'.time()
        ]);

        // 6. Create Activities
        $activity = Activity::create([
            'title' => 'Science Fair 2026',
            'description' => 'Annual school science fair exhibitions.',
            'event_date' => date('Y-m-d 09:00:00', strtotime('+7 days')),
            'location' => 'Main Hall',
        ]);
        
        $activity->activityLogs()->create(['user_id' => $student1->id, 'status' => 'registered']);
    }
}
