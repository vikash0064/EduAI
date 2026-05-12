<x-app-layout>
    <x-slot name="pageTitle">My Academic Results</x-slot>

    <div class="space-y-8 max-w-[1400px] mx-auto pb-12">
        <!-- Performance Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">analytics</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Average Score</p>
                        <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $stats['avg'] }}%</h3>
                    </div>
                </div>
                <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-1000" style="width: {{ $stats['avg'] }}%"></div>
                </div>
            </div>

            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-success/10 text-success flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">trending_up</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Highest Score</p>
                        <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $stats['highest'] }}%</h3>
                    </div>
                </div>
                <p class="text-[10px] text-on-surface-variant mt-4 font-medium uppercase tracking-widest">Personal Best</p>
            </div>

            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">history_edu</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Exams</p>
                        <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ $stats['total'] }}</h3>
                    </div>
                </div>
                <p class="text-[10px] text-on-surface-variant mt-4 font-medium uppercase tracking-widest">Graded Assessments</p>
            </div>

            <div class="glass p-6 rounded-[32px] shadow-soft border border-white/50 bg-white/40">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[28px]">military_tech</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">GPA</p>
                        <h3 class="text-2xl font-poppins font-bold text-on-surface">{{ number_format(($stats['avg'] / 100) * 4, 2) }}</h3>
                    </div>
                </div>
                <p class="text-[10px] text-on-surface-variant mt-4 font-medium uppercase tracking-widest">Cumulative GPA</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Subject Breakdown -->
            <div class="lg:col-span-1 space-y-6">
                <div class="glass p-8 rounded-[40px] shadow-xl border border-white/60 bg-white/50">
                    <h3 class="font-poppins font-bold text-on-surface mb-8">Subject Performance</h3>
                    <div class="space-y-6">
                        @foreach($subjectAverages as $subject => $avg)
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <p class="text-sm font-bold text-on-surface">{{ $subject }}</p>
                                <p class="text-sm font-poppins font-bold text-primary">{{ $avg }}%</p>
                            </div>
                            <div class="h-2 w-full bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary/80" style="width: {{ $avg }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Grading Scale Info -->
                <div class="bg-primary/5 rounded-[40px] p-8 border border-primary/10">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-primary">info</span>
                        <h4 class="text-sm font-bold text-primary uppercase tracking-widest">Grading Scale</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-3 bg-white/40 rounded-2xl">
                            <span class="text-xs font-bold">A+</span>
                            <span class="text-xs font-bold text-success">90-100</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/40 rounded-2xl">
                            <span class="text-xs font-bold">A</span>
                            <span class="text-xs font-bold text-success">80-89</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/40 rounded-2xl">
                            <span class="text-xs font-bold">B</span>
                            <span class="text-xs font-bold text-primary">70-79</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white/40 rounded-2xl">
                            <span class="text-xs font-bold">C</span>
                            <span class="text-xs font-bold text-secondary">60-69</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grade History Table -->
            <div class="lg:col-span-2">
                <div class="glass rounded-[40px] shadow-2xl border border-white/60 bg-white/50 overflow-hidden">
                    <div class="p-8 border-b border-outline-variant/10 flex items-center justify-between bg-surface-container-low/30">
                        <h3 class="font-poppins font-bold text-on-surface">Detailed Results History</h3>
                        <div class="flex gap-2">
                            <button class="p-2 rounded-xl bg-white/80 border border-outline-variant/20 hover:bg-white transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                            </button>
                            <button class="p-2 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all shadow-lg shadow-primary/20">
                                <span class="material-symbols-outlined text-[20px]">download</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-surface-container-low/50">
                                    <th class="px-8 py-5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Date</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Subject</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Exam Type</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Score</th>
                                    <th class="px-8 py-5 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                @forelse($grades as $grade)
                                <tr class="hover:bg-surface-container-low/30 transition-colors group">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-medium text-on-surface">{{ $grade->grade_date ? \Carbon\Carbon::parse($grade->grade_date)->format('M d, Y') : 'N/A' }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[18px] text-primary">book</span>
                                            </div>
                                            <p class="text-sm font-bold text-on-surface">{{ $grade->subject->name ?? 'N/A' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 bg-surface-container text-on-surface-variant rounded-full text-[10px] font-bold uppercase tracking-wider">
                                            {{ $grade->exam_type }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <p class="text-sm font-poppins font-bold {{ $grade->score >= 75 ? 'text-success' : ($grade->score >= 60 ? 'text-primary' : 'text-error') }}">
                                            {{ $grade->score }}<span class="text-[10px] opacity-40 ml-0.5">/{{ $grade->max_score }}</span>
                                        </p>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $grade->score >= 60 ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                                            <div class="w-1.5 h-1.5 rounded-full {{ $grade->score >= 60 ? 'bg-success' : 'bg-error' }}"></div>
                                            <span class="text-[10px] font-bold uppercase">{{ $grade->score >= 60 ? 'Passed' : 'Failed' }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center opacity-40">
                                        <span class="material-symbols-outlined text-5xl mb-4">history_edu</span>
                                        <p class="text-sm font-bold uppercase tracking-widest">No results published yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
