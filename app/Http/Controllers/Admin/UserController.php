<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role', 'student');
        
        $query = User::with('studentProfile');

        if (in_array($role, ['teacher', 'student', 'admin', 'parent'])) {
            $query->where('role', $role);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sq) use ($q) {
                $sq->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhereHas('studentProfile', function($spq) use ($q) {
                       $spq->where('enrollment_number', 'like', "%$q%");
                   });
            });
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users', 'role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,teacher,student,parent',
            'enrollment_number' => 'nullable|string|required_if:role,student',
            'teacher_id' => 'nullable|string|required_if:role,teacher|unique:teacher_profiles,teacher_id',
            'subject' => 'nullable|string|required_if:role,teacher',
            'department' => 'nullable|string|required_if:role,teacher',
            'grade_level' => 'nullable|string|required_if:role,student',
            'section' => 'nullable|string|required_if:role,student',
            'relationship' => 'nullable|string|required_if:role,parent',
            'phone' => 'nullable|string|required_if:role,parent',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:student_profiles,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            if ($validated['role'] === 'student') {
                $studentProfile = StudentProfile::create([
                    'user_id' => $user->id,
                    'enrollment_number' => $validated['enrollment_number'],
                    'grade_level' => $validated['grade_level'],
                    'section' => $validated['section'],
                ]);

                // Automatically create a Parent account for this student
                $parentUser = User::create([
                    'name' => 'Parent of ' . $validated['name'],
                    'email' => 'parent_' . $validated['email'],
                    'password' => Hash::make($validated['password']), // Same initial password
                    'role' => 'parent',
                ]);

                $parentProfile = \App\Models\ParentProfile::create([
                    'user_id' => $parentUser->id,
                    'relationship' => 'Parent',
                    'phone' => 'Not provided',
                ]);

                $parentProfile->students()->attach($studentProfile->id);

            } elseif ($validated['role'] === 'teacher') {
                \App\Models\TeacherProfile::create([
                    'user_id' => $user->id,
                    'teacher_id' => $validated['teacher_id'],
                    'subject' => $validated['subject'],
                    'department' => $validated['department'],
                ]);
            } elseif ($validated['role'] === 'parent') {
                $parentProfile = \App\Models\ParentProfile::create([
                    'user_id' => $user->id,
                    'relationship' => $validated['relationship'],
                    'phone' => $validated['phone'],
                ]);

                if (!empty($validated['student_ids'])) {
                    $parentProfile->students()->attach($validated['student_ids']);
                }
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User and profile created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(User $user)
    {
        $user->load(['studentProfile', 'parentProfile']);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'enrollment_number' => 'nullable|string',
            'grade_level' => 'nullable|string',
            'section' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        if ($user->role === 'student' && $user->studentProfile) {
            $user->studentProfile->update([
                'enrollment_number' => $request->enrollment_number,
                'grade_level' => $request->grade_level,
                'section' => $request->section,
            ]);
        } elseif ($user->role === 'parent' && $user->parentProfile) {
            $user->parentProfile->update([
                'relationship' => $request->relationship,
                'phone' => $request->phone,
            ]);

            if ($request->has('student_ids')) {
                $user->parentProfile->students()->sync($request->student_ids);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own administrator account.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index', ['role' => $user->role])->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user. They might have related records that prevent deletion.');
        }
    }
}
