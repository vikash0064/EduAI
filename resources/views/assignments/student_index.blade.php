<x-app-layout>
    <x-slot name="pageTitle">My Assignments</x-slot>

    <div class="space-y-8 max-w-[1400px] mx-auto pb-12">
        <!-- Stats Banner -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[32px]">assignment</span>
                </div>
                <div>
                    <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $assignments->count() }}</h3>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Assigned</p>
                </div>
            </div>

            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-success/10 text-success flex items-center justify-center">
                    <span class="material-symbols-outlined text-[32px]">task_alt</span>
                </div>
                <div>
                    <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $completedCount }}</h3>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Completed</p>
                </div>
            </div>

            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-error/10 text-error flex items-center justify-center">
                    <span class="material-symbols-outlined text-[32px]">pending_actions</span>
                </div>
                <div>
                    <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $pendingCount }}</h3>
                    <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pending</p>
                </div>
            </div>
        </div>

        <!-- Assignments List -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($assignments as $assignment)
            @php $submission = $assignment->submissions->first(); @endphp
            <div class="glass p-8 rounded-[40px] shadow-xl border border-white/60 bg-white/50 hover:shadow-2xl transition-all group relative overflow-hidden">
                @if($submission && $submission->status === 'graded')
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-success/10 rounded-full flex items-end justify-start p-6 text-success group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">military_tech</span>
                    </div>
                @endif

                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-surface-container-highest flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[28px]">book</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-primary uppercase tracking-widest">{{ $assignment->subject->name }}</p>
                            <h4 class="text-lg font-poppins font-bold text-on-surface mt-0.5">{{ $assignment->title }}</h4>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Due Date</p>
                        <p class="text-sm font-bold text-error">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <p class="text-sm text-on-surface-variant leading-relaxed mb-8 line-clamp-3">
                    {{ $assignment->description ?? 'No description provided.' }}
                </p>

                <div class="flex items-center justify-between pt-6 border-t border-outline-variant/10">
                    <div class="flex items-center gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Max Score</p>
                            <p class="text-sm font-poppins font-bold text-on-surface">{{ $assignment->max_score }} pts</p>
                        </div>
                        @if($submission)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-1">Your Score</p>
                            <p class="text-sm font-poppins font-bold {{ $submission->status === 'graded' ? 'text-success' : 'text-primary' }}">
                                {{ $submission->status === 'graded' ? $submission->score : 'Pending' }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if($submission)
                        <div class="flex items-center gap-2 px-4 py-2 bg-success/10 text-success rounded-2xl">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider">Submitted</span>
                        </div>
                    @else
                        <button class="px-6 py-2.5 bg-primary text-white rounded-2xl text-[11px] font-bold uppercase tracking-widest hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">upload_file</span>
                            Submit Now
                        </button>
                    @endif
                </div>

                @if($submission && $submission->feedback)
                <div class="mt-6 p-4 bg-surface-container-low rounded-2xl border border-outline-variant/10">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">comment</span>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Instructor Feedback</p>
                    </div>
                    <p class="text-xs text-on-surface-variant italic">"{{ $submission->feedback }}"</p>
                </div>
                @endif
            </div>
            @empty
            <div class="lg:col-span-2 py-24 text-center opacity-40">
                <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-6xl">assignment_late</span>
                </div>
                <h3 class="text-xl font-poppins font-bold text-on-surface">No Assignments Yet</h3>
                <p class="text-sm font-medium mt-2">Check back later for new coursework from your teachers.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
