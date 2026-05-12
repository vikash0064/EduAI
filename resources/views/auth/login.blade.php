<x-guest-layout>
<div class="min-h-screen flex">

    <!-- Left Illustration Panel -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col items-center justify-center p-12"
         style="background: linear-gradient(135deg, #3525cd 0%, #4f46e5 50%, #00687a 100%);">

        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-20"
             style="background: rgba(255,255,255,0.3); transform: translate(30%, -30%);"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full opacity-15"
             style="background: rgba(87,223,254,0.5); transform: translate(-30%, 30%);"></div>

        <!-- Floating Cards (decorative) -->
        <div class="absolute top-24 right-8 glass rounded-2xl p-4 shadow-xl text-white w-44">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-[16px]" style="color: #57dffe;">trending_up</span>
                <span class="text-xs font-semibold">GPA This Term</span>
            </div>
            <p class="text-2xl font-bold font-poppins">3.92</p>
            <p class="text-xs opacity-75 mt-1">Top 5% of class</p>
        </div>
        <div class="absolute bottom-32 right-12 glass rounded-2xl p-4 shadow-xl text-white w-48">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-[16px]" style="color: #57dffe;">psychology</span>
                <span class="text-xs font-semibold">AI Insight</span>
            </div>
            <p class="text-xs opacity-90">Review Fourier Transforms before Thursday's exam.</p>
        </div>
        <div class="absolute top-1/2 left-8 glass rounded-2xl p-4 shadow-xl text-white w-36" style="transform: translateY(-80%);">
            <p class="text-xs font-semibold mb-1">Attendance</p>
            <p class="text-2xl font-bold font-poppins">96%</p>
            <p class="text-xs opacity-75">This Month</p>
        </div>

        <!-- Center Content -->
        <div class="relative z-10 text-center text-white">
            <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6 shadow-xl">
                <span class="font-poppins text-3xl font-bold">E</span>
            </div>
            <h1 class="font-poppins text-4xl font-bold mb-4">EduAI Tracker</h1>
            <p class="text-lg opacity-80 max-w-xs mx-auto leading-relaxed">
                Real-time academic intelligence for students, teachers, and parents.
            </p>

            <div class="mt-10 grid grid-cols-3 gap-4 text-center">
                <div class="bg-white/10 rounded-2xl p-4">
                    <p class="text-2xl font-bold font-poppins">4.8K+</p>
                    <p class="text-xs opacity-70 mt-1">Students</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-4">
                    <p class="text-2xl font-bold font-poppins">320+</p>
                    <p class="text-xs opacity-70 mt-1">Teachers</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-4">
                    <p class="text-2xl font-bold font-poppins">98%</p>
                    <p class="text-xs opacity-70 mt-1">Satisfaction</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Login Panel -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-surface-container-low">
        <div class="w-full max-w-md">

            <!-- Logo (mobile) -->
            <div class="lg:hidden text-center mb-8">
                <span class="font-poppins text-3xl font-bold text-primary">EduAI</span>
            </div>

            <div class="glass rounded-3xl p-8 shadow-soft">
                <div class="mb-8">
                    <h2 class="font-poppins text-2xl font-bold text-on-surface">Welcome back!</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Sign in to your EduAI account</p>
                </div>

                <!-- Role Selector -->
                <div class="mb-6">
                    <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-3">Sign in as</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setRole('student')"
                                id="chip-student"
                                class="role-chip active flex-1 justify-center">
                            <span class="material-symbols-outlined text-[16px]">school</span>
                            Student
                        </button>
                        <button type="button" onclick="setRole('teacher')"
                                id="chip-teacher"
                                class="role-chip flex-1 justify-center">
                            <span class="material-symbols-outlined text-[16px]">cast_for_education</span>
                            Teacher
                        </button>
                        <button type="button" onclick="setRole('parent')"
                                id="chip-parent"
                                class="role-chip flex-1 justify-center">
                            <span class="material-symbols-outlined text-[16px]">family_restroom</span>
                            Parent
                        </button>
                        <button type="button" onclick="setRole('admin')"
                                id="chip-admin"
                                class="role-chip flex-1 justify-center">
                            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                            Admin
                        </button>
                    </div>
                </div>

                <!-- Session Status -->
                @if(session('status'))
                    <div class="mb-4 px-4 py-3 rounded-xl bg-success/10 text-success text-sm font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Errors -->
                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-xl bg-error/10 text-error text-sm">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="role_hint" id="role_hint" value="student">

                    <!-- Email / Login -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5" id="login-label">Email Address</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] transition-colors group-focus-within:text-primary" id="login-icon">mail</span>
                            <input type="text" name="login" id="login"
                                   value="{{ old('login') }}"
                                   required autofocus
                                   class="input-field pl-12"
                                   placeholder="you@school.edu">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">Password</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px] transition-colors group-focus-within:text-primary">lock</span>
                            <input type="password" name="password" id="password"
                                   required
                                   class="input-field pl-12 pr-12"
                                   placeholder="Enter your password">
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                                <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-outline-variant text-primary focus:ring-primary/20">
                            <span class="text-sm text-on-surface-variant">Remember me</span>
                        </label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-primary mt-2">
                        Sign In
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .input-field {
        width: 100%;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        padding-right: 1rem;
        padding-left: 3.25rem !important; /* Fixed overlap with icons */
        background: rgba(255,255,255,0.85);
        border: 1.5px solid #c7c4d8;
        border-radius: 0.75rem;
        font-size: 0.9rem;
        color: #1b1b24;
        transition: all 0.2s;
        outline: none;
        font-family: 'Inter', sans-serif;
    }
    .input-field:focus { border-color: #3525cd; box-shadow: 0 0 0 3px rgba(53,37,205,0.1); background: white; }
    .btn-primary {
        width: 100%;
        padding: 0.85rem 1.5rem;
        background: linear-gradient(135deg, #3525cd, #4f46e5);
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-size: 0.95rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(53,37,205,0.35); }
    .role-chip {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 0.45rem 0.75rem; border-radius: 9999px;
        font-size: 0.78rem; font-weight: 600; cursor: pointer;
        border: 1.5px solid transparent; transition: all 0.2s;
        background: rgba(53,37,205,0.06); color: #464555;
        font-family: 'Inter', sans-serif;
    }
    .role-chip.active { background: #3525cd; color: white; border-color: #3525cd; }
    .role-chip:hover:not(.active) { background: rgba(53,37,205,0.1); border-color: rgba(53,37,205,0.2); }
</style>

<script>
    function setRole(role) {
        document.querySelectorAll('.role-chip').forEach(c => c.classList.remove('active'));
        document.getElementById('chip-' + role).classList.add('active');
        document.getElementById('role_hint').value = role;
        
        const label = document.getElementById('login-label');
        const icon = document.getElementById('login-icon');
        const input = document.getElementById('login');
        
        if (role === 'student') {
            label.textContent = 'Email or Enrollment ID';
            icon.textContent = 'badge';
            input.placeholder = 'you@school.edu or EDU-2024-XXX';
        } else if (role === 'teacher') {
            label.textContent = 'Email or Teacher ID';
            icon.textContent = 'assignment_ind';
            input.placeholder = 'you@school.edu or TCH-2024-XXX';
        } else if (role === 'parent') {
            label.textContent = 'Parent Email or Child Enrollment ID';
            icon.textContent = 'badge';
            input.placeholder = 'you@email.com or CHILD-ID-XXX';
        } else {
            label.textContent = 'Administrator Email';
            icon.textContent = 'admin_panel_settings';
            input.placeholder = 'admin@example.com';
        }
    }
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eye-icon');
        if (pwd.type === 'password') { pwd.type = 'text'; icon.textContent = 'visibility_off'; }
        else { pwd.type = 'password'; icon.textContent = 'visibility'; }
    }
</script>
</x-guest-layout>
