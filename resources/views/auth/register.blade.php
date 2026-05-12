<x-guest-layout>
<div class="min-h-screen flex">

    <!-- Left Panel -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col items-center justify-center p-12"
         style="background: linear-gradient(135deg, #3525cd 0%, #4f46e5 60%, #00687a 100%);">
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-20"
             style="background: rgba(255,255,255,0.25); transform: translate(30%,-30%);"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full opacity-15"
             style="background: rgba(87,223,254,0.5); transform: translate(-30%,30%);"></div>

        <div class="relative z-10 text-center text-white">
            <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-6 shadow-xl">
                <span class="font-poppins text-3xl font-bold">E</span>
            </div>
            <h1 class="font-poppins text-4xl font-bold mb-4">Join EduAI</h1>
            <p class="text-lg opacity-80 max-w-xs mx-auto leading-relaxed">
                Track progress. Unlock potential. Achieve excellence.
            </p>
            <div class="mt-10 space-y-4">
                <div class="bg-white/10 rounded-2xl p-4 flex items-center gap-3 text-left">
                    <span class="material-symbols-outlined text-2xl" style="color:#57dffe;">psychology</span>
                    <div>
                        <p class="font-semibold text-sm">AI-Powered Insights</p>
                        <p class="text-xs opacity-70">Personalized study recommendations</p>
                    </div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 flex items-center gap-3 text-left">
                    <span class="material-symbols-outlined text-2xl" style="color:#57dffe;">trending_up</span>
                    <div>
                        <p class="font-semibold text-sm">Real-time Tracking</p>
                        <p class="text-xs opacity-70">Live attendance & grade monitoring</p>
                    </div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 flex items-center gap-3 text-left">
                    <span class="material-symbols-outlined text-2xl" style="color:#57dffe;">family_restroom</span>
                    <div>
                        <p class="font-semibold text-sm">Parent Connect</p>
                        <p class="text-xs opacity-70">Seamless school-home communication</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Register Panel -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-surface-container-low overflow-y-auto">
        <div class="w-full max-w-md my-8">

            <div class="lg:hidden text-center mb-8">
                <span class="font-poppins text-3xl font-bold text-primary">EduAI</span>
            </div>

            <div class="glass rounded-3xl p-8 shadow-soft">
                <div class="mb-6">
                    <h2 class="font-poppins text-2xl font-bold text-on-surface">Create Account</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Join EduAI Academic Progress Tracker</p>
                </div>

                <!-- Errors -->
                @if($errors->any())
                    <div class="mb-4 px-4 py-3 rounded-xl bg-error/10 text-error text-sm">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Role -->
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Register as</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach(['student'=>'school','teacher'=>'cast_for_education','parent'=>'family_restroom'] as $r=>$icon)
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $r }}" class="sr-only peer" {{ old('role','student')===$r?'checked':'' }}>
                                <div class="peer-checked:bg-primary peer-checked:text-white flex flex-col items-center gap-1 p-3 rounded-2xl border-2 border-outline-variant/30 peer-checked:border-primary text-on-surface-variant hover:border-primary/40 transition-all text-center">
                                    <span class="material-symbols-outlined text-[22px]">{{ $icon }}</span>
                                    <span class="text-xs font-semibold capitalize">{{ $r }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">Full Name</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="input-field pl-10" placeholder="Your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">Email Address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="input-field pl-10" placeholder="you@school.edu">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
                            <input type="password" name="password" required
                                   class="input-field pl-10" placeholder="Min 8 characters">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-semibold text-on-surface mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock_open</span>
                            <input type="password" name="password_confirmation" required
                                   class="input-field pl-10" placeholder="Repeat password">
                        </div>
                    </div>

                    <!-- Terms -->
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" required class="mt-0.5 rounded border-outline-variant text-primary focus:ring-primary/20">
                        <span class="text-xs text-on-surface-variant">
                            I agree to the <a href="#" class="text-primary font-semibold">Terms of Service</a> and <a href="#" class="text-primary font-semibold">Privacy Policy</a>
                        </span>
                    </label>

                    <!-- Submit -->
                    <button type="submit" class="btn-primary">
                        Create Account
                    </button>

                    <p class="text-center text-sm text-on-surface-variant">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Sign in</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .input-field {
        width: 100%; padding: 0.75rem 1rem;
        background: rgba(255,255,255,0.85); border: 1.5px solid #c7c4d8;
        border-radius: 0.75rem; font-size: 0.9rem; color: #1b1b24;
        transition: all 0.2s; outline: none; font-family: 'Inter', sans-serif;
    }
    .input-field:focus { border-color: #3525cd; box-shadow: 0 0 0 3px rgba(53,37,205,0.1); background: white; }
    .btn-primary {
        width: 100%; padding: 0.85rem 1.5rem;
        background: linear-gradient(135deg, #3525cd, #4f46e5);
        color: white; border: none; border-radius: 0.75rem;
        font-size: 0.95rem; font-weight: 600; font-family: 'Poppins', sans-serif;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(53,37,205,0.35); }
</style>
</x-guest-layout>
