<x-app-layout>
    <x-slot name="pageTitle">Assignment Management</x-slot>
    
    <div x-data="{ openModal: false }" class="space-y-8 max-w-[1400px] mx-auto pb-24">
        
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant mb-2">
                    <span>Mathematics</span>
                    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    <span class="text-primary">Department Portal</span>
                </nav>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Assignment Tracker</h1>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-5 py-2.5 bg-white border border-outline-variant/30 rounded-xl text-xs font-bold shadow-sm hover:bg-surface-container-low transition-all">Export Grades</button>
                <button @click="openModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Create Assignment
                </button>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-primary border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total Active</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-on-surface">{{ $assignments->count() }}</span>
                </div>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-teal-500 border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total Submissions</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-on-surface">{{ $totalSubmissions }}</span>
                    <span class="text-[10px] font-bold text-teal-500 uppercase ml-1">trending_up</span>
                </div>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-error border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Pending Grading</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-error">{{ $pendingGrading }}</span>
                </div>
            </div>
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-amber-500 border border-outline-variant/10">
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Avg Class Score</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-on-surface">{{ round($avgScore) }}%</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Assignments List -->
            <div class="lg:col-span-8 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-poppins font-bold text-xl text-on-surface">Recent Assignments</h2>
                    <div class="flex items-center gap-2">
                        <button class="px-4 py-1.5 rounded-xl bg-surface-container-low text-[11px] font-bold text-primary">Active</button>
                        <button class="px-4 py-1.5 rounded-xl text-[11px] font-bold text-on-surface-variant hover:bg-surface-container-low transition-all">Drafts</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-outline-variant/10">
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Assignment & Class</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Submissions</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Due Date</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @forelse($assignments as $assignment)
                            <tr class="group hover:bg-surface-container-low/30 transition-colors">
                                <td class="py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined">assignment</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $assignment->title }}</p>
                                            <p class="text-[10px] font-semibold text-on-surface-variant uppercase">{{ $assignment->subject->name ?? 'Subject' }} • {{ $assignment->grade_level }}-{{ $assignment->section }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <div class="flex flex-col gap-1.5">
                                        @php 
                                            $subCount = $assignment->submissions->count();
                                            $perc = 32 > 0 ? ($subCount / 32) * 100 : 0;
                                        @endphp
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-on-surface">{{ $subCount }}/32</span>
                                            <span class="text-[10px] font-bold text-on-surface-variant">{{ round($perc) }}%</span>
                                        </div>
                                        <div class="w-24 h-1.5 bg-surface-container-low rounded-full overflow-hidden">
                                            <div class="bg-primary h-full rounded-full" style="width: {{ $perc }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs font-bold text-on-surface">{{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}</p>
                                    <p class="text-[10px] font-semibold text-error uppercase">{{ \Carbon\Carbon::parse($assignment->due_date)->format('h:i A') }}</p>
                                </td>
                                <td class="py-5 text-right">
                                    <button class="px-4 py-2 bg-surface-container-low hover:bg-primary hover:text-white rounded-xl text-[11px] font-bold text-primary transition-all">View Submissions</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-on-surface-variant font-semibold">No assignments created yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Panel: Grading & AI -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Grading Progress -->
                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10">
                    <h3 class="font-poppins font-bold text-on-surface mb-6">Grading Progress</h3>
                    <div class="flex items-center gap-6 mb-6">
                        <div class="relative w-24 h-24">
                            <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                <path class="text-outline-variant/10" stroke-dasharray="100, 100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-teal-500" stroke-dasharray="78, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-xl font-poppins font-bold text-on-surface">78%</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                <span class="text-[11px] font-bold text-on-surface-variant uppercase">Graded: 64</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-error"></span>
                                <span class="text-[11px] font-bold text-on-surface-variant uppercase">To Grade: 18</span>
                            </div>
                        </div>
                    </div>
                    <button class="w-full py-3 bg-surface-container-low text-primary text-xs font-bold rounded-2xl hover:bg-primary hover:text-white transition-all">Start Grading</button>
                </div>

                <!-- AI Insights -->
                <div class="bg-indigo-600 rounded-[32px] p-6 shadow-soft text-on-primary">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                        <h3 class="font-poppins font-bold text-[14px]">EduAI Insight</h3>
                    </div>
                    <p class="text-xs leading-relaxed opacity-90">
                        8 students struggled with Question 4 in the "Quadratic Equations" task. Consider a quick recap session on "Completing the Square" before the next class.
                    </p>
                    <div class="mt-6 p-4 rounded-2xl bg-white/10 border border-white/20">
                        <p class="text-[10px] font-bold uppercase opacity-80 mb-2">Recommended Resource</p>
                        <p class="text-xs font-bold">Algebra Practice Set #4</p>
                        <button class="mt-3 w-full py-2 bg-white text-primary rounded-xl text-[10px] font-bold">Share with Class</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Assignment Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div @click="openModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in" @click.away="openModal = false">
                <div class="p-8 border-b border-outline-variant/10">
                    <h2 class="text-2xl font-poppins font-bold text-on-surface">Create New Assignment</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Fill in the details to publish a new task to your class.</p>
                </div>
                
                <form action="{{ route('teacher.assignments.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Assignment Title</label>
                            <input type="text" name="title" required placeholder="e.g. Chemical Bonding Lab Report" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Subject</label>
                            <select name="subject_id" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Target Grade</label>
                            <div class="flex gap-2">
                                <select name="grade_level" class="flex-1 px-4 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                    <option value="10">Grade 10</option>
                                    <option value="11">Grade 11</option>
                                    <option value="12">Grade 12</option>
                                </select>
                                <select name="section" class="w-20 px-4 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Due Date</label>
                            <input type="datetime-local" name="due_date" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Max Points</label>
                            <input type="number" name="max_score" value="100" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Instructions (Optional)</label>
                            <textarea name="description" rows="3" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm focus:ring-2 focus:ring-primary/20"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-low rounded-2xl transition-all">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Publish Assignment</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-app-layout>
