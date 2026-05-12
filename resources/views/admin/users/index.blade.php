<x-app-layout>
    <x-slot name="pageTitle">User Management</x-slot>
    <x-slot name="searchPlaceholder">Search by name, email or ID...</x-slot>

    <div x-data="{ openModal: {{ $errors->any() ? 'true' : 'false' }}, role: '{{ old('role', 'student') }}' }" class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-success/10 border border-success/20 text-success p-4 rounded-2xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-error/10 border border-error/20 text-error p-4 rounded-2xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">error</span>
                <p class="text-sm font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Validation Errors for Modal -->
        @if($errors->any() && !session('success') && !session('error'))
            <div class="bg-error/10 border border-error/20 text-error p-4 rounded-2xl mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined">report</span>
                    <p class="text-sm font-bold">Please correct the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 ml-9">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">System Users</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Manage students, teachers, and staff members</p>
            </div>
            <button @click="openModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                Add New User
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-primary border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total Students</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format(\App\Models\User::where('role', 'student')->count()) }}</h3>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-teal-500 border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Active Teachers</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format(\App\Models\User::where('role', 'teacher')->count()) }}</h3>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-amber-500 border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total Parents</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format(\App\Models\User::where('role', 'parent')->count()) }}</h3>
            </div>
        </div>

        <!-- User Table -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4 bg-surface-container-low p-1.5 rounded-2xl">
                    <a href="{{ route('admin.users.index', ['role' => 'student']) }}" 
                       class="px-6 py-2 rounded-xl {{ $role === 'student' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:bg-white' }} text-xs font-bold transition-all">Students</a>
                    <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}" 
                       class="px-6 py-2 rounded-xl {{ $role === 'teacher' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:bg-white' }} text-xs font-bold transition-all">Teachers</a>
                    <a href="{{ route('admin.users.index', ['role' => 'parent']) }}" 
                       class="px-6 py-2 rounded-xl {{ $role === 'parent' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant hover:bg-white' }} text-xs font-bold transition-all">Parents</a>
                </div>
                <div class="flex items-center gap-2">
                    <button class="w-10 h-10 rounded-xl border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low transition-all">
                        <span class="material-symbols-outlined text-[20px]">filter_list</span>
                    </button>
                    <button class="w-10 h-10 rounded-xl border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low transition-all">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-outline-variant/10">
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">User Profile</th>
                            @if($role === 'student')
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Class / Section</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Enrollment ID</th>
                            @elseif($role === 'teacher')
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Subject</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Department</th>
                            @elseif($role === 'parent')
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Linked Students</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Contact</th>
                            @else
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Role</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Access Level</th>
                            @endif
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($users as $user)
                        <tr class="group hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-primary font-bold text-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $user->name }}</p>
                                        <p class="text-[11px] font-semibold text-on-surface-variant">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            @if($role === 'student')
                                <td class="py-5">
                                    <p class="text-xs font-bold text-on-surface">Grade {{ $user->studentProfile->grade_level ?? 'N/A' }}-{{ $user->studentProfile->section ?? '' }}</p>
                                </td>
                                <td class="py-5">
                                    <p class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $user->studentProfile->enrollment_number ?? 'NO ID' }}</p>
                                </td>
                            @elseif($role === 'teacher')
                                <td class="py-5">
                                    <p class="text-xs font-bold text-on-surface">{{ $user->teacherProfile->subject ?? 'N/A' }}</p>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs font-bold text-on-surface-variant">{{ $user->teacherProfile->department ?? 'N/A' }}</p>
                                </td>
                            @elseif($role === 'parent')
                                <td class="py-5">
                                    @if($user->parentProfile && $user->parentProfile->students->count() > 0)
                                        <div class="flex -space-x-2">
                                            @foreach($user->parentProfile->students as $student)
                                                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-[10px] font-bold border-2 border-white" title="{{ $student->user->name }}">
                                                    {{ strtoupper(substr($student->user->name, 0, 1)) }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-on-surface-variant uppercase italic">No students linked</span>
                                    @endif
                                </td>
                                <td class="py-5">
                                    <p class="text-xs font-bold text-on-surface">{{ $user->parentProfile->phone ?? 'N/A' }}</p>
                                </td>
                            @else
                                <td class="py-5">
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-5">
                                    <span class="text-xs font-bold text-on-surface-variant italic">Full System Access</span>
                                </td>
                            @endif
                            <td class="py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-white hover:shadow-md transition-all">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-all">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add User Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div @click="openModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in" @click.away="openModal = false">
                <div class="p-8 border-b border-outline-variant/10">
                    <h2 class="text-2xl font-poppins font-bold text-on-surface">Add New System User</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Create profiles for students, teachers or admins.</p>
                </div>
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Role Assignment</label>
                            <div class="flex items-center gap-3 bg-surface-container-low p-1 rounded-2xl">
                                <button type="button" @click="role = 'student'" :class="role === 'student' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all">Student</button>
                                <button type="button" @click="role = 'teacher'" :class="role === 'teacher' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all">Teacher</button>
                                <button type="button" @click="role = 'parent'" :class="role === 'parent' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all">Parent</button>
                                <button type="button" @click="role = 'admin'" :class="role === 'admin' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="flex-1 py-2.5 rounded-xl text-xs font-bold transition-all">Admin</button>
                            </div>
                            <input type="hidden" name="role" x-model="role">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Full Name</label>
                            <input type="text" name="name" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Email Address</label>
                            <input type="email" name="email" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Account Password</label>
                            <input type="password" name="password" required placeholder="Min. 6 characters" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <!-- Role Specific Fields -->
                        <template x-if="role === 'teacher'">
                            <div class="contents">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Teacher ID</label>
                                    <input type="text" name="teacher_id" required placeholder="TCH-2024-XXX" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Primary Subject</label>
                                    <input type="text" name="subject" required placeholder="e.g. Mathematics" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Department</label>
                                    <input type="text" name="department" required placeholder="e.g. Science Dept" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                            </div>
                        </template>

                        <template x-if="role === 'student'">
                            <div class="contents">
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Enrollment ID</label>
                                    <input type="text" name="enrollment_number" required placeholder="EDU-2023-XXX" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Grade & Section</label>
                                    <div class="flex gap-2">
                                        <select name="grade_level" required class="flex-1 px-4 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                            <option value="10">Grade 10</option>
                                            <option value="11">Grade 11</option>
                                            <option value="12">Grade 12</option>
                                        </select>
                                        <select name="section" required class="w-24 px-4 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="role === 'parent'">
                            <div class="contents">
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Relationship</label>
                                    <input type="text" name="relationship" required placeholder="e.g. Father, Mother" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Phone Number</label>
                                    <input type="text" name="phone" required placeholder="+1 (555) 000-0000" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Link Students</label>
                                    <select name="student_ids[]" multiple class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm h-32">
                                        @foreach(\App\Models\StudentProfile::with('user')->get() as $student)
                                            <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->enrollment_number }})</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[10px] text-on-surface-variant mt-2 italic">Hold Ctrl/Cmd to select multiple students.</p>
                                </div>
                            </div>
                        </template>

                        <template x-if="role === 'teacher'">
                            <div class="col-span-2">
                                <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Primary Subject Assignment</label>
                                <select name="subject" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                    <option value="">Select Subject</option>
                                    @foreach(\App\Models\Subject::all() as $subject)
                                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </template>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Password</label>
                            <input type="password" name="password" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-low rounded-2xl transition-all">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Create Account</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
