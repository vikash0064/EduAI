<x-app-layout>
    <x-slot name="pageTitle">Student Dashboard</x-slot>
    <x-slot name="searchPlaceholder">Search courses, assignments or AI tutor...</x-slot>

    @if(!$profile)
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
            <div class="w-24 h-24 bg-surface-container-low rounded-full flex items-center justify-center text-primary mb-6">
                <span class="material-symbols-outlined text-5xl">person_off</span>
            </div>
            <h2 class="text-2xl font-poppins font-bold text-on-surface">Profile Not Found</h2>
            <p class="text-on-surface-variant mt-2 max-w-md">Your academic profile has not been set up yet.</p>
        </div>
    @else
    <div x-data="{ timelineFilter: 'all' }" class="space-y-8 max-w-[1400px] mx-auto pb-12">

        <!-- Welcome Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-[28px] bg-primary/10 border-4 border-white shadow-soft flex items-center justify-center text-primary text-3xl font-bold overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                    <p class="text-sm font-semibold text-on-surface-variant mt-1 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                        Grade {{ $profile->grade_level }} – Section {{ $profile->section }}
                    </p>
                </div>
            </div>
            <button class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-[20px]">bolt</span>
                Quick Actions
            </button>
        </div>

        <!-- Summary Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-600/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">GPA Score</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-indigo-600">{{ $gpa }}</span>
                    <span class="text-xs font-bold text-indigo-400">/ 4.0</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-teal-600/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Attendance</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-teal-600">{{ $attendancePercentage }}%</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-600/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Assignments</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-amber-600">{{ $completedAssignments }}</span>
                    <span class="text-xs font-bold text-on-surface-variant ml-1">/ {{ $totalAssignments }}</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-cyan-600/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Global Rank</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-cyan-600">#{{ $globalRank }}</span>
                </div>
                <p class="text-xs font-semibold text-on-surface-variant mt-2">Out of {{ $totalStudentsInGrade }} students</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Detailed Report Card Section -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10 h-full overflow-hidden">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="font-poppins font-bold text-xl text-on-surface">Academic Performance</h2>
                        <span class="material-symbols-outlined text-primary/40">bar_chart</span>
                    </div>

                    <div class="space-y-8">
                        @foreach($gradesGrouped as $subjectName => $data)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-on-surface">{{ $subjectName }}</h4>
                                </div>
                                <span class="text-xs font-bold text-primary px-3 py-1 bg-primary/5 rounded-lg">{{ $data['avg'] }}% Avg</span>
                            </div>

                            <div class="pl-11 space-y-3 border-l-2 border-outline-variant/10 ml-4">
                                @foreach($data['exams'] as $exam)
                                <div class="flex items-center justify-between group">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">{{ $exam['type'] }}</span>
                                        <span class="text-[9px] font-semibold text-on-surface-variant/60">{{ $exam['date'] }}</span>
                                    </div>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">{{ $exam['score'] }}</span>
                                        <span class="text-[9px] font-bold text-on-surface-variant/40">/ {{ $exam['max'] }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @if($gradesGrouped->isEmpty())
                    <div class="py-12 text-center">
                        <span class="material-symbols-outlined text-4xl text-outline-variant/20 mb-3">grading</span>
                        <p class="text-xs font-bold text-on-surface-variant opacity-60">No grades recorded yet.</p>
                    </div>
                    @endif

                    <div class="mt-12 bg-primary/5 rounded-[24px] p-6 border border-primary/10">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-symbols-outlined text-primary text-[20px]">psychology</span>
                            <span class="text-xs font-bold text-primary uppercase">AI Study Buddy</span>
                        </div>
                        <p class="text-xs leading-relaxed text-on-surface font-medium">
                            Your performance in <span class="font-bold">Calculus</span> has dropped by 8% recently. Focus on "Integration" practice.
                        </p>
                    </div>
                </div>
            </div>

            <!-- New Grouped Learning Timeline (PREMIUM STYLE) -->
            <div class="lg:col-span-8 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="font-poppins font-bold text-xl text-on-surface">Learning Timeline</h2>
                        <p class="text-xs font-semibold text-on-surface-variant mt-1"> Chronological feed of your academic journey</p>
                    </div>
                    <div class="flex bg-surface-container-low p-1 rounded-xl">
                        <button @click="timelineFilter = 'all'" :class="timelineFilter === 'all' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all">All</button>
                        <button @click="timelineFilter = 'grade'" :class="timelineFilter === 'grade' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all">Grades</button>
                        <button @click="timelineFilter = 'attendance'" :class="timelineFilter === 'attendance' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase transition-all">Attendance</button>
                    </div>
                </div>

                <div class="space-y-10">
                    @php
                        // Grouping logic (simplified for Blade)
                        $groupedTimeline = $timeline->groupBy(function($item) {
                            return str_contains($item['time'], 'ago') && !str_contains($item['time'], 'day') ? 'Recent Activity' : 'Earlier Activity';
                        });
                    @endphp

                    @foreach($groupedTimeline as $header => $items)
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <span class="text-[11px] font-bold text-on-surface-variant uppercase tracking-[0.2em]">{{ $header }}</span>
                            <div class="flex-1 h-[1px] bg-outline-variant/10"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($items as $item)
                            <div x-show="timelineFilter === 'all' || timelineFilter === '{{ $item['type'] }}'" 
                                 x-transition class="p-5 rounded-[24px] bg-white border border-outline-variant/10 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl {{ $item['color'] }} flex items-center justify-center text-white flex-shrink-0 shadow-lg shadow-current/20">
                                    <span class="material-symbols-outlined text-[20px]">{{ $item['icon'] }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider opacity-60">{{ $item['title'] }}</p>
                                        <span class="text-[9px] font-bold text-on-surface-variant">{{ $item['time'] }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-on-surface">{{ $item['content'] }}</h4>
                                    <div class="mt-3 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="text-[10px] font-bold text-primary hover:underline">View Details</button>
                                        <span class="text-outline-variant/30 text-[10px]">•</span>
                                        <button class="text-[10px] font-bold text-on-surface-variant hover:underline">Dismiss</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Upcoming Milestone Banner -->
        <div class="bg-indigo-600 rounded-[32px] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-1/3 h-full bg-gradient-to-l from-white/10 to-transparent"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-[32px]">event</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-poppins font-bold text-white">Physics Finals: Next Milestone</h3>
                        <p class="text-white/70 text-sm font-medium mt-1">Based on your learning pattern, you are 85% ready for this exam.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-white font-bold">Oct 28, 2023</p>
                        <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest">14 Days Left</p>
                    </div>
                    <button class="px-8 py-4 bg-white text-indigo-600 rounded-2xl font-bold text-sm shadow-2xl hover:scale-105 transition-all">Start Review</button>
                </div>
            </div>
        </div>

    </div>
    @endif

</x-app-layout>
