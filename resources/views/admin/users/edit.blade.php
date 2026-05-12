<x-app-layout>
    <x-slot name="pageTitle">Edit User: {{ $user->name }}</x-slot>

    <div class="max-w-3xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Edit Profile</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Update user account details and role-specific data.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-outline-variant/30 text-on-surface-variant rounded-2xl text-sm font-bold shadow-sm hover:bg-surface-container-low transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-8">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Role Assignment</label>
                        <div class="flex items-center gap-3 bg-surface-container-low p-1.5 rounded-2xl">
                            <div class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold transition-all {{ $user->role === 'student' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant opacity-50' }}">Student</div>
                            <div class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold transition-all {{ $user->role === 'teacher' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant opacity-50' }}">Teacher</div>
                            <div class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold transition-all {{ $user->role === 'admin' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant opacity-50' }}">Admin</div>
                            <div class="flex-1 py-2.5 rounded-xl text-center text-xs font-bold transition-all {{ $user->role === 'parent' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant opacity-50' }}">Parent</div>
                        </div>
                        <input type="hidden" name="role" value="{{ $user->role }}">
                        <p class="text-[10px] text-on-surface-variant/60 mt-2 italic px-2">Role changes are restricted for security. Create a new user if you need to change the role.</p>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                               class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                               class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                    </div>

                    @if($user->role === 'student' && $user->studentProfile)
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Enrollment ID</label>
                            <input type="text" name="enrollment_number" value="{{ old('enrollment_number', $user->studentProfile->enrollment_number) }}" 
                                   class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Grade & Section</label>
                            <div class="flex gap-3">
                                <select name="grade_level" class="flex-1 px-4 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm">
                                    @foreach(['10', '11', '12'] as $g)
                                        <option value="{{ $g }}" {{ old('grade_level', $user->studentProfile->grade_level) == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                                    @endforeach
                                </select>
                                <select name="section" class="w-24 px-4 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm">
                                    @foreach(['A', 'B', 'C', 'D'] as $s)
                                        <option value="{{ $s }}" {{ old('section', $user->studentProfile->section) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    @if($user->role === 'parent' && $user->parentProfile)
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Relationship</label>
                            <input type="text" name="relationship" value="{{ old('relationship', $user->parentProfile->relationship) }}" 
                                   class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->parentProfile->phone) }}" 
                                   class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">Linked Students</label>
                            <select name="student_ids[]" multiple class="w-full px-5 py-3.5 bg-surface-container-low border-none rounded-2xl text-sm h-32">
                                @php $currentIds = $user->parentProfile->students->pluck('id')->toArray(); @endphp
                                @foreach(\App\Models\StudentProfile::with('user')->get() as $student)
                                    <option value="{{ $student->id }}" {{ in_array($student->id, $currentIds) ? 'selected' : '' }}>
                                        {{ $student->user->name }} ({{ $student->enrollment_number }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-on-surface-variant mt-2 italic px-2">Hold Ctrl/Cmd to select multiple students.</p>
                        </div>
                    @endif

                    <div class="col-span-2">
                        <div class="p-6 bg-surface-container-low/50 rounded-3xl border border-outline-variant/10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-amber-500 text-[20px]">security</span>
                                <h3 class="text-sm font-bold text-on-surface">Security</h3>
                            </div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-3">New Password (Leave blank to keep current)</label>
                            <input type="password" name="password" 
                                   class="w-full px-5 py-3.5 bg-white border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 shadow-sm">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="flex-1 py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-xl shadow-primary/20 hover:-translate-y-0.5 transition-all">
                        Update User Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
