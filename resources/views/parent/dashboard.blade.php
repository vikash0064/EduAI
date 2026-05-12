<x-app-layout>
    <x-slot name="pageTitle">Parent Dashboard</x-slot>

    <div class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Welcome & Child Selector -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight tracking-tight">Parent Portal</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Monitoring academic growth for {{ Auth::user()->name }}'s family.</p>
            </div>

            @if($children->count() > 1)
            <div class="flex items-center gap-3 bg-white p-2 rounded-[24px] shadow-sm border border-outline-variant/10">
                <p class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest ml-4 mr-2">Select Student</p>
                <div class="flex items-center gap-2">
                    @foreach($children as $child)
                        <a href="{{ route('parent.dashboard', ['child_id' => $child->id]) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $selectedStudent->id === $child->id ? 'bg-primary text-on-primary shadow-lg shadow-primary/20' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                            {{ explode(' ', $child->user->name)[0] }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        @if(isset($selectedStudent))
        <!-- Student Overview Header -->
        <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 rounded-bl-[200px] -mr-20 -mt-20"></div>
            
            <div class="relative flex flex-col md:flex-row items-center gap-8">
                <div class="w-32 h-32 rounded-[40px] bg-surface-container flex items-center justify-center text-primary overflow-hidden border-4 border-white shadow-xl">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedStudent->user->name) }}&background=4f46e5&color=fff&size=256" class="w-full h-full object-cover">
                </div>
                <div class="text-center md:text-left flex-1">
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <h2 class="text-3xl font-poppins font-black text-on-surface">{{ $selectedStudent->user->name }}</h2>
                        <span class="px-4 py-1.5 bg-success/10 text-success text-[10px] font-black uppercase tracking-widest rounded-full w-fit mx-auto md:mx-0">Active Enrollment</span>
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-6">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">school</span>
                            <span class="text-sm font-bold text-on-surface">Grade {{ $selectedStudent->grade_level }} • Section {{ $selectedStudent->section }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-[20px]">fingerprint</span>
                            <span class="text-sm font-bold text-on-surface-variant">ID: {{ $selectedStudent->enrollment_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('parent.child.results', $selectedStudent) }}" class="flex flex-col items-center justify-center w-24 h-24 bg-surface-container-low rounded-3xl hover:bg-primary/5 hover:text-primary transition-all border border-outline-variant/5">
                        <span class="material-symbols-outlined mb-2">assessment</span>
                        <span class="text-[10px] font-bold uppercase">Results</span>
                    </a>
                    <a href="{{ route('parent.child.attendance', $selectedStudent) }}" class="flex flex-col items-center justify-center w-24 h-24 bg-surface-container-low rounded-3xl hover:bg-primary/5 hover:text-primary transition-all border border-outline-variant/5">
                        <span class="material-symbols-outlined mb-2">calendar_month</span>
                        <span class="text-[10px] font-bold uppercase">Attendance</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Academic GPA</p>
                        <h4 class="text-2xl font-poppins font-black text-on-surface">{{ $gpa }}</h4>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-indigo-50 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: 85%"></div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <span class="material-symbols-outlined">how_to_reg</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Attendance</p>
                        <h4 class="text-2xl font-poppins font-black text-on-surface">{{ $attendancePercentage }}%</h4>
                    </div>
                </div>
                <div class="h-1.5 w-full bg-teal-50 rounded-full overflow-hidden">
                    <div class="h-full bg-teal-600 rounded-full" style="width: {{ $attendancePercentage }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-error/5 flex items-center justify-center text-error">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Unpaid Fees</p>
                        <h4 class="text-2xl font-poppins font-black text-error">₹{{ number_format($pendingFees) }}</h4>
                    </div>
                </div>
                <a href="{{ route('parent.child.fees', $selectedStudent) }}" class="text-[10px] font-bold text-primary uppercase tracking-widest hover:underline">Pay Now →</a>
            </div>

            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined">event</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Next Exam</p>
                        <h4 class="text-lg font-poppins font-black text-on-surface">
                            {{ $upcomingExams->first() ? $upcomingExams->first()->subject_name : 'No Schedule' }}
                        </h4>
                    </div>
                </div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase">
                    {{ $upcomingExams->first() ? $upcomingExams->first()->date->format('M d, Y') : 'Check back later' }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Performance -->
            <div class="lg:col-span-2 bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-poppins font-bold text-on-surface">Recent Performance</h3>
                    <a href="{{ route('parent.child.results', $selectedStudent) }}" class="text-xs font-bold text-primary hover:underline">Full Report Card</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentGrades as $grade)
                    <div class="flex items-center justify-between p-5 bg-surface-container-low/50 rounded-[24px] border border-outline-variant/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-[20px]">book</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-on-surface">{{ $grade->subject->name }}</p>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $grade->exam_type }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-black text-on-surface">{{ $grade->score }}<span class="text-[10px] font-bold text-on-surface-variant">/{{ $grade->max_score }}</span></p>
                            <p class="text-[10px] font-bold {{ $grade->score >= 75 ? 'text-success' : 'text-amber-600' }} uppercase tracking-widest">
                                {{ $grade->score >= 75 ? 'Excellent' : 'Stable' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center opacity-40 italic font-medium">No results recorded yet.</div>
                    @endforelse
                </div>
            </div>

            <!-- Exam Schedule -->
            <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                <h3 class="text-xl font-poppins font-bold text-on-surface mb-8">Exam Schedule</h3>
                <div class="space-y-6">
                    @forelse($upcomingExams as $exam)
                    <div class="relative pl-8 border-l-2 border-primary/20 pb-2">
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 border-primary"></div>
                        <p class="text-xs font-black text-primary uppercase tracking-widest">{{ $exam->date->format('M d') }}</p>
                        <h4 class="text-sm font-bold text-on-surface mt-1">{{ $exam->subject_name }}</h4>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase mt-1">{{ $exam->title }} • {{ $exam->date->format('h:i A') }}</p>
                    </div>
                    @empty
                    <div class="py-12 text-center opacity-40 italic font-medium">No upcoming exams.</div>
                    @endforelse
                </div>
                
                <div class="mt-8 p-6 bg-primary/5 rounded-[32px] border border-primary/10">
                    <p class="text-xs font-bold text-primary mb-2 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">info</span>
                        AI Insight
                    </p>
                    <p class="text-[11px] font-semibold text-on-surface-variant leading-relaxed">
                        {{ $selectedStudent->user->name }}'s performance is trending upwards in STEM subjects. Focused revision in Mathematics for the upcoming finals is recommended.
                    </p>
                </div>
            </div>
        </div>
        @else
        <div class="py-24 flex flex-col items-center justify-center text-center opacity-40">
            <span class="material-symbols-outlined text-6xl mb-4">family_restroom</span>
            <p class="text-lg font-bold">No children linked to your account.</p>
            <p class="text-sm">Please contact administration to connect your family profiles.</p>
        </div>
        @endif

    </div>
</x-app-layout>
