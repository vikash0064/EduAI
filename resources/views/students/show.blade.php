<x-app-layout>
    <x-slot name="pageTitle">Student Profile: {{ $student->user->name }}</x-slot>

    <div class="space-y-8 pb-20">
        <!-- Header & Top Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url()->previous() }}" class="w-10 h-10 rounded-full bg-white shadow-sm border border-outline-variant/10 flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">{{ $student->user->name }}</h1>
                    <p class="text-sm font-semibold text-on-surface-variant mt-1 uppercase tracking-widest">Enrollment: {{ $student->enrollment_number }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="px-6 py-2.5 bg-white text-on-surface border border-outline-variant/10 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Edit Profile
                </button>
                <button class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:scale-105 transition-all">
                    <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                    Generate Report
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Sidebar: Basic Info & Parents -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Profile Card -->
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-24 bg-primary/5"></div>
                    <div class="relative pt-4 flex flex-col items-center text-center">
                        <div class="w-32 h-32 rounded-[40px] bg-white p-1.5 shadow-2xl mb-6">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=4f46e5&color=fff&size=256" class="w-full h-full object-cover rounded-[34px]">
                        </div>
                        <h3 class="text-xl font-poppins font-bold text-on-surface">{{ $student->user->name }}</h3>
                        <p class="text-xs font-bold text-primary uppercase mt-1">Class {{ $student->grade_level }}-{{ $student->section }}</p>
                        
                        <div class="w-full grid grid-cols-2 gap-4 mt-8">
                            <div class="p-4 bg-surface-container-low/50 rounded-3xl border border-outline-variant/5">
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">Attendance</p>
                                <p class="text-lg font-bold text-teal-600">{{ $attendancePercentage }}%</p>
                            </div>
                            <div class="p-4 bg-surface-container-low/50 rounded-3xl border border-outline-variant/5">
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">GPA</p>
                                <p class="text-lg font-bold text-indigo-600">{{ number_format($gpa, 1) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4 border-t border-outline-variant/5 pt-8">
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary/60">cake</span>
                            <div>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">Date of Birth</p>
                                <p class="text-xs font-bold text-on-surface">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary/60">mail</span>
                            <div>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">Email Address</p>
                                <p class="text-xs font-bold text-on-surface">{{ $student->user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="material-symbols-outlined text-primary/60">location_on</span>
                            <div>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">Address</p>
                                <p class="text-xs font-bold text-on-surface">{{ $student->address ?? 'New Delhi, India' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Details -->
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                    <h3 class="text-lg font-poppins font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">family_restroom</span>
                        Parental Contact
                    </h3>
                    @forelse($student->parents as $parent)
                    <div class="p-5 rounded-3xl bg-surface-container-low border border-outline-variant/10 mb-4">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary font-bold shadow-sm">
                                {{ substr($parent->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-on-surface">{{ $parent->user->name }}</h4>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $parent->relationship ?? 'Parent' }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="tel:{{ $parent->phone }}" class="flex items-center justify-between text-xs font-bold text-on-surface-variant hover:text-primary transition-colors">
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">call</span> Phone</span>
                                <span>{{ $parent->phone ?? 'N/A' }}</span>
                            </a>
                            <div class="flex items-center justify-between text-xs font-bold text-on-surface-variant">
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">work</span> Occupation</span>
                                <span>{{ $parent->occupation ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs font-bold text-on-surface-variant/40 text-center py-4 italic">No parent info linked.</p>
                    @endforelse
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Academic Performance Tabs -->
                <div x-data="{ tab: 'grades' }" class="space-y-6">
                    <div class="flex bg-white p-1.5 rounded-[24px] shadow-sm border border-outline-variant/10 w-fit">
                        <button @click="tab = 'grades'" :class="tab === 'grades' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant hover:bg-surface-container-low'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">grade</span> Grades
                        </button>
                        <button @click="tab = 'attendance'" :class="tab === 'attendance' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant hover:bg-surface-container-low'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span> Attendance
                        </button>
                        <button @click="tab = 'assignments'" :class="tab === 'assignments' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant hover:bg-surface-container-low'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">assignment</span> Assignments
                        </button>
                        <button @click="tab = 'fees'" :class="tab === 'fees' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant hover:bg-surface-container-low'" class="px-6 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">payments</span> Fees
                        </button>
                    </div>

                    <!-- Grades Panel -->
                    <div x-show="tab === 'grades'" x-transition class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                        <h3 class="text-xl font-poppins font-bold text-on-surface mb-8">Detailed Subject Performance</h3>
                        <div class="space-y-4">
                            @foreach($student->grades->groupBy('subject.name') as $subjectName => $subjectGrades)
                            <div class="p-6 rounded-3xl bg-surface-container-low/30 border border-outline-variant/5">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined text-[20px]">school</span>
                                        </div>
                                        <h4 class="text-sm font-bold text-on-surface">{{ $subjectName }}</h4>
                                    </div>
                                    <span class="text-sm font-bold text-primary">{{ round($subjectGrades->avg('score')) }}% Avg</span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($subjectGrades as $g)
                                    <div class="bg-white p-3 rounded-2xl border border-outline-variant/5 shadow-sm">
                                        <p class="text-[9px] font-bold text-on-surface-variant uppercase mb-1">{{ $g->exam_type }}</p>
                                        <div class="flex items-baseline gap-1">
                                            <span class="text-sm font-bold text-on-surface">{{ $g->score }}</span>
                                            <span class="text-[9px] font-bold text-on-surface-variant">/ {{ $g->max_score }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Attendance Panel -->
                    <div x-show="tab === 'attendance'" x-transition class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-poppins font-bold text-on-surface">Attendance History</h3>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                                    <span class="text-[10px] font-bold text-on-surface-variant">Present</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-error"></span>
                                    <span class="text-[10px] font-bold text-on-surface-variant">Absent</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-2">
                            <!-- Simple visual calendar or list -->
                            @foreach($student->attendances->take(28) as $att)
                            <div class="aspect-square rounded-xl flex flex-col items-center justify-center text-[10px] font-bold shadow-sm border border-outline-variant/5
                                {{ $att->status === 'present' ? 'bg-teal-50 text-teal-700' : 'bg-red-50 text-red-700' }}">
                                <span>{{ $att->date->format('d') }}</span>
                                <span class="text-[7px] opacity-60">{{ $att->date->format('M') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Assignments Panel -->
                    <div x-show="tab === 'assignments'" x-transition class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                        <h3 class="text-xl font-poppins font-bold text-on-surface mb-8">Recent Submissions</h3>
                        <div class="space-y-4">
                            @foreach($student->submissions as $submission)
                            <div class="flex items-center justify-between p-5 rounded-3xl bg-surface-container-low/30 border border-outline-variant/5">
                                <div class="flex items-center gap-4">
                                    <span class="material-symbols-outlined text-primary/60">description</span>
                                    <div>
                                        <h4 class="text-sm font-bold text-on-surface">{{ $submission->assignment->title }}</h4>
                                        <p class="text-[10px] font-bold text-on-surface-variant uppercase">Submitted: {{ $submission->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $submission->grade ? 'bg-teal-100 text-teal-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $submission->grade ? 'Graded: ' . $submission->grade : 'Pending' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fees Panel -->
                    <div x-show="tab === 'fees'" x-transition class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                        <h3 class="text-xl font-poppins font-bold text-on-surface mb-8">Fee Payment Status</h3>
                        <div class="space-y-4">
                            @foreach($student->fees as $fee)
                            <div class="flex items-center justify-between p-6 rounded-3xl bg-surface-container-low border border-outline-variant/10">
                                <div class="flex items-center gap-6">
                                    <div class="w-12 h-12 rounded-2xl {{ $fee->status === 'paid' ? 'bg-teal-50 text-teal-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center">
                                        <span class="material-symbols-outlined">{{ $fee->status === 'paid' ? 'check_circle' : 'pending_actions' }}</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-on-surface">{{ $fee->title }}</h4>
                                        <p class="text-[10px] font-bold text-on-surface-variant uppercase">Due: {{ $fee->due_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-on-surface">₹{{ number_format($fee->amount) }}</p>
                                    <span class="text-[10px] font-bold uppercase {{ $fee->status === 'paid' ? 'text-teal-600' : 'text-red-600' }}">{{ $fee->status }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
