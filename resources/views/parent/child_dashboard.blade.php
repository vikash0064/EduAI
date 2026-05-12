<x-app-layout>
    <x-slot name="pageTitle">{{ $student->user->name }}'s Academic Progress</x-slot>

    <div class="space-y-8 pb-20">
        <!-- Child Profile Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="w-24 h-24 rounded-[32px] bg-white p-1 shadow-xl border border-outline-variant/10">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=4f46e5&color=fff&size=128" class="w-full h-full object-cover rounded-[28px]">
                </div>
                <div>
                    <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">{{ $student->user->name }}</h1>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-full uppercase">Class {{ $student->grade_level }}-{{ $student->section }}</span>
                        <span class="px-3 py-1 bg-surface-container-low text-on-surface-variant text-[10px] font-bold rounded-full uppercase">Roll: #{{ 1000 + $student->id }}</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('messages.index') }}" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:scale-105 transition-all">
                    <span class="material-symbols-outlined text-[20px]">chat_bubble</span>
                    Contact Teacher
                </a>
                <button class="px-6 py-2.5 bg-white text-on-surface border border-outline-variant/10 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    Download Report
                </button>
            </div>
        </div>

        <!-- Analytics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10 group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">how_to_reg</span>
                    </div>
                    <span class="text-[10px] font-bold text-teal-600 uppercase">Attendance</span>
                </div>
                <h4 class="text-3xl font-poppins font-bold text-on-surface">{{ $attendancePercentage }}%</h4>
                <p class="text-[11px] font-bold text-on-surface-variant mt-1">Total classes attended</p>
            </div>

            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10 group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">auto_awesome</span>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 uppercase">Overall GPA</span>
                </div>
                <h4 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format($gpa, 1) }}</h4>
                <p class="text-[11px] font-bold text-on-surface-variant mt-1">Based on recent exams</p>
            </div>

            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10 group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <span class="text-[10px] font-bold text-red-600 uppercase">Pending Fees</span>
                </div>
                <h4 class="text-3xl font-poppins font-bold text-on-surface">₹{{ number_format($pendingFees) }}</h4>
                <p class="text-[11px] font-bold text-on-surface-variant mt-1">Due by next week</p>
            </div>

            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10 group hover:border-primary/30 transition-all">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <span class="material-symbols-outlined">assignment_late</span>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 uppercase">Assignments</span>
                </div>
                <h4 class="text-3xl font-poppins font-bold text-on-surface">2</h4>
                <p class="text-[11px] font-bold text-on-surface-variant mt-1">Pending submissions</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Results -->
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-poppins font-bold text-on-surface">Recent Academic Results</h3>
                        <a href="{{ route('parent.child.results', $student) }}" class="text-xs font-bold text-primary hover:underline">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentGrades as $grade)
                        <div class="flex items-center justify-between p-5 rounded-3xl bg-surface-container-low/30 border border-outline-variant/5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[20px]">school</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-on-surface">{{ $grade->subject->name }}</h4>
                                    <p class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $grade->exam_type }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-on-surface">{{ $grade->score }}/{{ $grade->max_score }}</p>
                                @php $pct = ($grade->score / $grade->max_score) * 100; @endphp
                                <span class="text-[10px] font-bold {{ $pct >= 80 ? 'text-teal-600' : ($pct >= 60 ? 'text-amber-600' : 'text-red-600') }}">{{ round($pct) }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Upcoming Exams -->
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                    <h3 class="text-xl font-poppins font-bold text-on-surface mb-8">Upcoming Exam Schedule</h3>
                    <div class="space-y-4">
                        @foreach($upcomingExams as $exam)
                        <div class="flex items-center gap-6 p-5 rounded-3xl border border-outline-variant/10 hover:bg-surface-container-low/30 transition-all">
                            <div class="w-16 h-16 rounded-2xl bg-surface-container-low flex flex-col items-center justify-center">
                                <span class="text-lg font-bold">{{ $exam->date->format('d') }}</span>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $exam->date->format('M') }}</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-on-surface">{{ $exam->title }}</h4>
                                <p class="text-[11px] font-bold text-on-surface-variant mt-1">{{ $exam->subject->name }} • {{ $exam->date->format('h:i A') }}</p>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant">calendar_month</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Navigation Menu -->
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                    <h3 class="text-lg font-poppins font-bold text-on-surface mb-6">Quick Actions</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('parent.child.attendance', $student) }}" class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low hover:bg-primary hover:text-white group transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined">calendar_today</span>
                                <span class="text-xs font-bold">Attendance Records</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                        <a href="{{ route('parent.child.results', $student) }}" class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low hover:bg-primary hover:text-white group transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined">grade</span>
                                <span class="text-xs font-bold">Academic Results</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                        <a href="{{ route('parent.child.fees', $student) }}" class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low hover:bg-primary hover:text-white group transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined">payments</span>
                                <span class="text-xs font-bold">Fee Status</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                        <a href="#" class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low hover:bg-primary hover:text-white group transition-all">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined">description</span>
                                <span class="text-xs font-bold">Assignments</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                        </a>
                    </div>
                </div>

                <!-- Teacher Remarks -->
                <div class="bg-indigo-600 rounded-[40px] p-8 shadow-soft text-white relative overflow-hidden">
                    <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-white/10 text-9xl">format_quote</span>
                    <h3 class="text-lg font-poppins font-bold mb-6">Teacher's Remarks</h3>
                    <div class="space-y-6">
                        <div class="p-5 bg-white/10 rounded-3xl border border-white/20">
                            <p class="text-xs leading-relaxed italic">"{{ $student->user->name }} is showing great progress in Mathematics. Participating more in class discussions would be even better."</p>
                            <div class="flex items-center gap-3 mt-4">
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold">RS</div>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Prof. Rajesh Sharma</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
