<x-app-layout>
    <x-slot name="pageTitle">Grades & Exam Management</x-slot>

    <div x-data="{ showNewExam: false, showEditGrade: false, editingGrade: null }" class="space-y-8">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-poppins font-bold text-on-surface">Term 2 • Academic Year 2024–25</h2>
                <p class="text-sm font-medium text-on-surface-variant">Manage assessments and monitor class performance</p>
            </div>
            <div class="flex gap-3">
                <button @click="showNewExam = true" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:scale-105 transition-all">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    New Exam
                </button>
                <button class="px-6 py-2.5 bg-white text-on-surface border border-outline-variant/10 rounded-xl font-bold text-sm flex items-center gap-2 hover:bg-surface-container-low transition-all">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                    Report Cards
                </button>
            </div>
        </div>

        <!-- Subject Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            @php
                $icons = [
                    'Mathematics' => 'functions',
                    'Physics' => 'auto_awesome',
                    'Chemistry' => 'science',
                    'Literature' => 'menu_book',
                    'Computer Science' => 'computer'
                ];
            @endphp
            @foreach($subjectStats as $stat)
            <div class="bg-white p-6 rounded-[32px] shadow-soft border border-outline-variant/10 group hover:border-primary/30 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined">{{ $icons[$stat->subject->name] ?? 'school' }}</span>
                </div>
                <h4 class="text-sm font-bold text-on-surface mb-1">{{ $stat->subject->name }}</h4>
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-poppins font-bold text-on-surface">Avg: {{ number_format($stat->avg_score, 0) }}%</span>
                </div>
                <div class="mt-4 h-1 w-full bg-surface-container-low rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width: {{ $stat->avg_score }}%"></div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Exam Schedule -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-8 rounded-[40px] shadow-soft border border-outline-variant/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-poppins font-bold text-on-surface">Upcoming Exam Schedule</h3>
                        <div class="flex bg-surface-container-low p-1 rounded-xl">
                            <button class="px-4 py-1.5 bg-white text-primary rounded-lg text-xs font-bold shadow-sm">Term 2</button>
                            <button class="px-4 py-1.5 text-on-surface-variant text-xs font-bold">Term 1</button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($upcomingExams as $exam)
                        <div class="flex items-center gap-6 p-5 rounded-3xl border border-outline-variant/5 hover:bg-surface-container-low/30 transition-all group">
                            <div class="flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-surface-container-low text-on-surface border border-outline-variant/10">
                                <span class="text-lg font-bold leading-none">{{ $exam->date->format('d') }}</span>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase">{{ $exam->date->format('M') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-on-surface group-hover:text-primary transition-colors">{{ $exam->title }}</h4>
                                <p class="text-[11px] font-bold text-on-surface-variant/60 flex items-center gap-3 mt-1">
                                    <span>Grade {{ $exam->grade_level }} – {{ $exam->section ?? 'All' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-outline-variant/30"></span>
                                    <span>{{ $exam->date->format('h:i A') }}</span>
                                    <span class="w-1 h-1 rounded-full bg-outline-variant/30"></span>
                                    <span>Room {{ $exam->room ?? 'TBD' }}</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $exam->status === 'upcoming' ? 'bg-amber-100 text-amber-700' : ($exam->status === 'completed' ? 'bg-teal-100 text-teal-700' : 'bg-indigo-100 text-indigo-700') }} uppercase">
                                    {{ $exam->status }}
                                </span>
                                <button class="text-on-surface-variant hover:text-on-surface"><span class="material-symbols-outlined text-[20px]">more_vert</span></button>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 flex flex-col items-center justify-center text-center opacity-40">
                            <span class="material-symbols-outlined text-5xl mb-3">calendar_today</span>
                            <p class="text-sm font-bold">No upcoming exams scheduled.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Gradebook Table -->
                <div class="bg-white p-8 rounded-[40px] shadow-soft border border-outline-variant/10 overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-poppins font-bold text-on-surface">Gradebook</h3>
                        <div class="flex gap-4">
                            <select class="bg-surface-container-low border-none rounded-xl text-xs font-bold text-on-surface focus:ring-primary/20">
                                <option>All Subjects</option>
                            </select>
                            <select class="bg-surface-container-low border-none rounded-xl text-xs font-bold text-on-surface focus:ring-primary/20">
                                <option>All Exams</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-8">
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low/30">
                                <tr>
                                    <th class="px-8 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Student</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Subject</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Exam Type</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Score</th>
                                    <th class="px-8 py-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                @forelse($allGrades as $grade)
                                <tr class="hover:bg-surface-container-low/20 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-primary/5 flex items-center justify-center text-primary text-xs font-bold">
                                                {{ substr($grade->studentProfile->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-on-surface">{{ $grade->studentProfile->user->name }}</p>
                                                <p class="text-[9px] font-bold text-on-surface-variant uppercase">ID: EDU-{{ 1000 + $grade->studentProfile->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-bold text-on-surface">{{ $grade->subject->name }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-3 py-1 rounded-full bg-surface-container-low text-[10px] font-bold text-on-surface uppercase">{{ $grade->exam_type }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-on-surface">{{ $grade->score }}</span>
                                            <span class="text-[10px] font-bold text-on-surface-variant">/ {{ $grade->max_score }}</span>
                                            @php $pct = ($grade->score / $grade->max_score) * 100; @endphp
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold {{ $pct >= 80 ? 'bg-teal-50 text-teal-700' : ($pct >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                                                {{ number_format($pct, 0) }}%
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="showEditGrade = true; editingGrade = {{ json_encode($grade) }}" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-all">
                                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                            </button>
                                            <form action="{{ route('teacher.grades.destroy', $grade) }}" method="POST" onsubmit="return confirm('Delete this grade?')">
                                                @csrf @method('DELETE')
                                                <button class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center opacity-40">
                                        <span class="material-symbols-outlined text-4xl mb-2">quiz</span>
                                        <p class="text-sm font-bold">No grades recorded yet.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Entry & Analytics -->
            <div class="space-y-8">
                <!-- Quick Entry -->
                <div class="bg-white p-8 rounded-[40px] shadow-soft border border-outline-variant/10">
                    <h3 class="text-lg font-poppins font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">edit_note</span>
                        Quick Grade Entry
                    </h3>
                    <form action="{{ route('teacher.grades.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <select name="student_profile_id" required class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                            @endforeach
                        </select>
                        <select name="subject_id" required class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="exam_type" placeholder="Exam Type (e.g., Midterm)" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <input type="number" name="score" placeholder="Score" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                            <input type="number" name="max_score" placeholder="Max" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                        </div>
                        <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] transition-all">
                            Save Grade
                        </button>
                    </form>
                </div>

                <!-- Class Performance -->
                <div class="bg-white p-8 rounded-[40px] shadow-soft border border-outline-variant/10">
                    <h3 class="text-lg font-poppins font-bold text-on-surface mb-6">Class Performance</h3>
                    <div class="space-y-5">
                        @php 
                            $total = array_sum($distribution);
                            $labels = [
                                'A' => ['title' => 'A Grade (90–100)', 'color' => 'bg-teal-500'],
                                'B' => ['title' => 'B Grade (75–89)', 'color' => 'bg-indigo-500'],
                                'C' => ['title' => 'C Grade (60–74)', 'color' => 'bg-amber-500'],
                                'F' => ['title' => 'Below 60', 'color' => 'bg-red-500'],
                            ];
                        @endphp
                        @foreach($labels as $key => $info)
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-on-surface-variant">{{ $info['title'] }}</span>
                                <span class="text-on-surface">{{ $total > 0 ? round(($distribution[$key]/$total)*100) : 0 }}%</span>
                            </div>
                            <div class="h-1.5 w-full bg-surface-container-low rounded-full overflow-hidden">
                                <div class="h-full {{ $info['color'] }}" style="width: {{ $total > 0 ? ($distribution[$key]/$total)*100 : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Top Performers -->
                <div class="bg-white p-8 rounded-[40px] shadow-soft border border-outline-variant/10">
                    <h3 class="text-lg font-poppins font-bold text-on-surface mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500">military_tech</span>
                        Top Performers
                    </h3>
                    <div class="space-y-4">
                        @foreach($topPerformers as $idx => $student)
                        <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-surface-container-low transition-all">
                            <div class="w-8 h-8 rounded-lg {{ $idx === 0 ? 'bg-amber-100 text-amber-600' : ($idx === 1 ? 'bg-slate-100 text-slate-600' : 'bg-orange-100 text-orange-600') }} flex items-center justify-center font-bold text-xs">
                                {{ $idx + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-on-surface">{{ $student->user->name }}</p>
                                <p class="text-[10px] font-bold text-on-surface-variant">{{ number_format($student->grades_avg_score, 1) }}% Avg</p>
                            </div>
                            <span class="material-symbols-outlined text-teal-500 text-sm">trending_up</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- New Exam Modal -->
        <div x-show="showNewExam" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showNewExam = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[40px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-8 border-b border-outline-variant/10 flex items-center justify-between bg-primary/5">
                    <h3 class="text-xl font-poppins font-bold text-on-surface">Schedule New Exam</h3>
                    <button @click="showNewExam = false" class="w-10 h-10 rounded-full hover:bg-white flex items-center justify-center transition-all"><span class="material-symbols-outlined">close</span></button>
                </div>
                <form action="{{ route('exams.store') }}" method="POST" class="p-8 space-y-5">
                    @csrf
                    <div>
                        <label class="text-[10px] font-bold text-on-surface-variant uppercase ml-1 mb-2 block">Exam Title</label>
                        <input type="text" name="title" required placeholder="e.g., Mathematics Finals" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-on-surface-variant uppercase ml-1 mb-2 block">Subject</label>
                            <select name="subject_id" required class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-on-surface-variant uppercase ml-1 mb-2 block">Grade Level</label>
                            <input type="text" name="grade_level" required placeholder="12 - A" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-on-surface-variant uppercase ml-1 mb-2 block">Date & Time</label>
                            <input type="datetime-local" name="date" required class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-on-surface-variant uppercase ml-1 mb-2 block">Room</label>
                            <input type="text" name="room" placeholder="Lab 1" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 mt-4">
                        Confirm Schedule
                    </button>
                </form>
            </div>
        </div>

        <!-- Edit Grade Modal -->
        <div x-show="showEditGrade" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="showEditGrade = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[40px] shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-8 border-b border-outline-variant/10">
                    <h3 class="text-xl font-poppins font-bold text-on-surface">Edit Grade</h3>
                </div>
                <form :action="'/teacher/grades/' + editingGrade?.id" method="POST" class="p-8 space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-2 block">Exam Type</label>
                        <input type="text" name="exam_type" :value="editingGrade?.exam_type" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-on-surface-variant mb-2 block">Score</label>
                        <input type="number" name="score" :value="editingGrade?.score" class="w-full bg-surface-container-low border-none rounded-2xl text-xs font-bold p-4">
                    </div>
                    <button type="submit" class="w-full py-4 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20">
                        Update Grade
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
