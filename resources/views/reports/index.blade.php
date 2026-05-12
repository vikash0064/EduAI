<x-app-layout>
    <x-slot name="pageTitle">Academic Reports</x-slot>

    <div x-data="{ openModal: false }" class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-success/10 border border-success/20 text-success p-4 rounded-2xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Academic Reports</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Generate and download comprehensive student progress reports.</p>
            </div>
            <button @click="openModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-[20px]">add_chart</span>
                Generate New Report
            </button>
        </div>

        <!-- Report Type Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Performance -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 group cursor-pointer hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <span class="material-symbols-outlined text-[24px]">analytics</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors">arrow_forward</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface">Academic Performance</h3>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Grades & GPA</p>
                <p class="text-2xl font-poppins font-black text-on-surface mt-4">{{ $counts['performance'] }} <span class="text-[10px] font-bold text-on-surface-variant uppercase">Generated</span></p>
            </div>

            <!-- Attendance -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 group cursor-pointer hover:border-teal-500/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <span class="material-symbols-outlined text-[24px]">how_to_reg</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-teal-600 transition-colors">arrow_forward</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface">Attendance Analytics</h3>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Daily & Monthly</p>
                <p class="text-2xl font-poppins font-black text-on-surface mt-4">{{ $counts['attendance'] }} <span class="text-[10px] font-bold text-on-surface-variant uppercase">Generated</span></p>
            </div>

            <!-- Behavioral -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 group cursor-pointer hover:border-amber-500/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined text-[24px]">psychology</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-amber-600 transition-colors">arrow_forward</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface">Behavioral Report</h3>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Teacher Feedback</p>
                <p class="text-2xl font-poppins font-black text-on-surface mt-4">{{ $counts['behavioral'] }} <span class="text-[10px] font-bold text-on-surface-variant uppercase">Generated</span></p>
            </div>

            <!-- Exam Results -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 group cursor-pointer hover:border-error/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-error/5 flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[24px]">quiz</span>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant group-hover:text-error transition-colors">arrow_forward</span>
                </div>
                <h3 class="text-sm font-bold text-on-surface">Examination Results</h3>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Term Finals</p>
                <p class="text-2xl font-poppins font-black text-on-surface mt-4">{{ $counts['exam'] }} <span class="text-[10px] font-bold text-on-surface-variant uppercase">Generated</span></p>
            </div>
        </div>

        <!-- Recent Reports List -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-bold text-on-surface">Recently Generated Reports</h3>
                <div class="flex items-center gap-4 bg-surface-container-low p-1.5 rounded-2xl">
                    <button class="px-6 py-2 rounded-xl bg-white shadow-sm text-xs font-bold text-primary">All Students</button>
                    <button class="px-6 py-2 rounded-xl text-xs font-bold text-on-surface-variant hover:bg-white transition-all">My Classes</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-outline-variant/10">
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Report Name</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Student</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Date Generated</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($reports as $report)
                        <tr class="group hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-5">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-primary">description</span>
                                    <p class="text-sm font-bold text-on-surface">{{ $report->name }}</p>
                                </div>
                            </td>
                            <td class="py-5">
                                <p class="text-sm font-semibold text-on-surface">{{ $report->studentProfile->user->name }}</p>
                            </td>
                            <td class="py-5 text-sm font-medium text-on-surface-variant">
                                {{ $report->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-5">
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider
                                    {{ $report->status === 'Ready' ? 'bg-success/10 text-success' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-white hover:shadow-md transition-all">
                                        <span class="material-symbols-outlined text-[18px]">download</span>
                                    </button>
                                    <button class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-white hover:shadow-md transition-all">
                                        <span class="material-symbols-outlined text-[18px]">share</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant font-medium italic">No reports generated yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Generate Report Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div @click="openModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in" @click.away="openModal = false">
                <div class="p-8 border-b border-outline-variant/10">
                    <h2 class="text-2xl font-poppins font-bold text-on-surface">New Analysis Request</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Select parameters to generate a new intelligence report.</p>
                </div>
                
                <form action="{{ route('admin.reports.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Student Assignment</label>
                            <select name="student_profile_id" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                <option value="">Select Student...</option>
                                @foreach(\App\Models\StudentProfile::with('user')->get() as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name }} ({{ $student->enrollment_number }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Report Methodology</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="performance" checked class="hidden peer">
                                    <div class="p-4 rounded-2xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <p class="text-xs font-bold text-on-surface group-hover:text-primary">Academic Performance</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="attendance" class="hidden peer">
                                    <div class="p-4 rounded-2xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <p class="text-xs font-bold text-on-surface group-hover:text-primary">Attendance Matrix</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="behavioral" class="hidden peer">
                                    <div class="p-4 rounded-2xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <p class="text-xs font-bold text-on-surface group-hover:text-primary">Behavioral Analysis</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="type" value="exam" class="hidden peer">
                                    <div class="p-4 rounded-2xl border border-outline-variant/30 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                        <p class="text-xs font-bold text-on-surface group-hover:text-primary">Examination Results</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Custom Report Name</label>
                            <input type="text" name="name" required placeholder="e.g. Quarterly Academic Review Q1" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-low rounded-2xl transition-all">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Generate Analysis</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
