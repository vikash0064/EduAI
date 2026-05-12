<x-app-layout>
    <x-slot name="pageTitle">Account Settings</x-slot>

    <div class="max-w-[1000px] mx-auto space-y-12 pb-20">
        <!-- Profile Header Card -->
        <div class="glass p-10 rounded-[48px] shadow-2xl border border-white/60 bg-white/50 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
            
            <div class="relative">
                <div class="w-32 h-32 rounded-[32px] bg-primary/10 flex items-center justify-center text-primary border-4 border-white shadow-xl">
                    <span class="material-symbols-outlined text-[64px]">person</span>
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-success rounded-2xl border-4 border-white flex items-center justify-center text-white shadow-lg">
                    <span class="material-symbols-outlined text-[20px]">verified</span>
                </div>
            </div>

            <div class="text-center md:text-left flex-1">
                <h2 class="text-3xl font-poppins font-bold text-on-surface">{{ auth()->user()->name }}</h2>
                <p class="text-on-surface-variant font-medium mt-1">{{ auth()->user()->email }}</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-6">
                    <span class="px-4 py-1.5 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider border border-primary/20">
                        {{ ucfirst(auth()->user()->role) }} Account
                    </span>
                    <span class="px-4 py-1.5 bg-surface-container-highest text-on-surface-variant rounded-full text-xs font-bold uppercase tracking-wider">
                        Active Session
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-12">
            <!-- Update Profile Information -->
            <section class="glass p-10 rounded-[48px] shadow-soft border border-white/60 bg-white/50">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">manage_accounts</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-poppins font-bold text-on-surface">Personal Information</h3>
                        <p class="text-xs text-on-surface-variant font-medium mt-0.5">Update your name and primary email address</p>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest ml-4">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest ml-4">Email Address</label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8">
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-xs font-bold text-success">Saved Successfully.</p>
                        @endif
                        <button type="submit" class="px-8 py-3.5 bg-primary text-white rounded-[24px] text-xs font-bold uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

            <!-- Update Password -->
            <section class="glass p-10 rounded-[48px] shadow-soft border border-white/60 bg-white/50">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">lock_reset</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-poppins font-bold text-on-surface">Security & Password</h3>
                        <p class="text-xs text-on-surface-variant font-medium mt-0.5">Ensure your account is using a long, random password</p>
                    </div>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="current_password" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest ml-4">Current Password</label>
                            <input id="current_password" name="current_password" type="password" 
                                   class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest ml-4">New Password</label>
                            <input id="password" name="password" type="password" 
                                   class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest ml-4">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" 
                                   class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-primary/20 font-sans transition-all" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 mt-8">
                        @if (session('status') === 'password-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-xs font-bold text-success">Password Updated.</p>
                        @endif
                        <button type="submit" class="px-8 py-3.5 bg-indigo-600 text-white rounded-[24px] text-xs font-bold uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">security</span>
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

            <!-- Danger Zone -->
            <section class="bg-error/5 p-10 rounded-[48px] border border-error/10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-error/10 text-error flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">heart_broken</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-poppins font-bold text-error">Danger Zone</h3>
                        <p class="text-xs text-error/60 font-medium mt-0.5">Permanent account deletion</p>
                    </div>
                </div>

                <div class="bg-white/60 p-6 rounded-[32px] border border-error/5 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="text-xs font-medium text-on-surface-variant max-w-xl">
                        Once your account is deleted, all of its resources and data will be permanently removed. Please download any data you wish to retain before proceeding.
                    </p>
                    
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                            class="px-8 py-3.5 bg-error text-white rounded-[24px] text-xs font-bold uppercase tracking-widest hover:bg-error/90 transition-all shadow-lg shadow-error/20 whitespace-nowrap">
                        Delete Account
                    </button>
                </div>
            </section>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-poppins font-bold text-on-surface mb-4">
                Are you sure you want to delete your account?
            </h2>

            <p class="text-sm text-on-surface-variant mb-8">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="space-y-2">
                <label for="delete_password" class="sr-only">Password</label>
                <input id="delete_password" name="password" type="password" placeholder="Confirm your password"
                       class="w-full bg-surface-container-low border-none rounded-[20px] px-6 py-4 text-sm focus:ring-2 focus:ring-error/20 font-sans transition-all" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-4 mt-10">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-8 py-3.5 bg-surface-container-highest text-on-surface rounded-[24px] text-xs font-bold uppercase tracking-widest hover:bg-surface-container-high transition-all">
                    Cancel
                </button>

                <button type="submit" class="px-8 py-3.5 bg-error text-white rounded-[24px] text-xs font-bold uppercase tracking-widest hover:bg-error/90 transition-all shadow-lg shadow-error/20">
                    Delete Permanently
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
