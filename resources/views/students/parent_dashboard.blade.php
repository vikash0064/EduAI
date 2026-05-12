<x-app-layout>
    <x-slot name="pageTitle">Parent Dashboard</x-slot>
    <x-slot name="searchPlaceholder">Search child progress, subjects...</x-slot>

    <div class="space-y-6 max-w-[1400px] mx-auto">

        <!-- Top Row: Student Spotlight + AI Insights -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Student Spotlight -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-soft border border-outline-variant/10">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <span class="text-xs font-semibold text-secondary uppercase tracking-widest px-3 py-1 bg-secondary/10 rounded-full">Student Spotlight</span>
                        <h1 class="font-poppins text-2xl font-bold text-on-surface mt-3">Alex Jenkins</h1>
                        <p class="text-sm text-on-surface-variant mt-0.5">Grade 8 – Section B | Academic Session 2024-25</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-on-surface-variant font-semibold uppercase tracking-wider">Current GPA</p>
                        <p class="font-poppins text-3xl font-bold text-primary mt-1">
                            {{ $childProfile->gpa ?? '3.85' }}
                        </p>
                    </div>
                </div>

                <!-- Subject Cards -->
                @php
                    $childSubjects = [
                        ['Mathematics', 'primary', 92],
                        ['Physics', 'secondary', 85],
                        ['Literature', '#f97316', 78],
                        ['Computer Sci', 'primary', 95],
                    ];
                @endphp
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($childSubjects as [$subject, $color, $mastery])
                    <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10 hover:bg-white transition-colors">
                        <p class="text-xs font-semibold text-on-surface-variant mb-2">{{ $subject }}</p>
                        <div class="h-1.5 w-full bg-surface-container-highest rounded-full overflow-hidden mb-2">
                            <div class="h-full rounded-full bg-primary" style="width: {{ $mastery }}%"></div>
                        </div>
                        <p class="text-sm font-bold text-on-surface">Mastery: <span class="text-primary">{{ $mastery }}%</span></p>
                    </div>
                    @endforeach
                </div>

                <!-- Attendance + Homework Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

                    <!-- Attendance Donut -->
                    <div class="bg-surface-container-low rounded-2xl p-5 flex items-center gap-5">
                        <div class="relative flex-shrink-0">
                            <svg width="90" height="90" viewBox="0 0 90 90">
                                <circle cx="45" cy="45" r="38" fill="transparent" stroke="#e4e1ee" stroke-width="9"/>
                                <circle cx="45" cy="45" r="38" fill="transparent" stroke="#3525cd"
                                        stroke-width="9" stroke-linecap="round"
                                        stroke-dasharray="239" stroke-dashoffset="24"
                                        transform="rotate(-90 45 45)"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="font-poppins text-base font-bold text-primary">90%</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Attendance</p>
                            <p class="text-xs text-on-surface-variant mt-1">Current Month</p>
                            <div class="flex gap-4 mt-2">
                                <div><p class="text-sm font-bold text-on-surface">18 Days</p><p class="text-xs text-on-surface-variant">Present</p></div>
                                <div><p class="text-sm font-bold text-error">2 Days</p><p class="text-xs text-on-surface-variant">Absent</p></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Homework -->
                    <div class="bg-surface-container-low rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-bold text-on-surface">Pending Homework</p>
                            <a href="#" class="text-xs font-semibold text-primary hover:underline">View All</a>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 bg-white rounded-xl p-3">
                                <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-[16px]">functions</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-on-surface">Quadratic Equations</p>
                                    <p class="text-[11px] text-on-surface-variant">Mathematics • Due Tomorrow</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-error/10 text-error">Urgent</span>
                            </div>
                            <div class="flex items-center gap-3 bg-white rounded-xl p-3">
                                <div class="w-8 h-8 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                    <span class="material-symbols-outlined text-[16px]">science</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-on-surface">Cell Division Project</p>
                                    <p class="text-[11px] text-on-surface-variant">Biology • Due in 3 days</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-surface-container-highest text-on-surface-variant">Standard</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Insights for Parents -->
            <div class="bg-white rounded-3xl p-6 shadow-soft border border-outline-variant/10 flex flex-col">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[20px]">psychology</span>
                    </div>
                    <h3 class="font-poppins font-bold text-on-surface">AI Insights for Parents</h3>
                </div>
                <div class="space-y-4 flex-1">
                    <div class="flex gap-3 p-4 rounded-2xl bg-surface-container-low hover:bg-white border border-outline-variant/10 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-primary text-[20px] flex-shrink-0 mt-0.5">trending_down</span>
                        <div>
                            <p class="text-xs font-bold text-primary mb-1">Focus on Literature</p>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Alex's reading comprehension scores dipped 5% this week. Consider 20 mins of shared reading.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 rounded-2xl bg-surface-container-low hover:bg-white border border-outline-variant/10 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-secondary text-[20px] flex-shrink-0 mt-0.5">quiz</span>
                        <div>
                            <p class="text-xs font-bold text-secondary mb-1">Upcoming Math Quiz</p>
                            <p class="text-xs text-on-surface-variant leading-relaxed">AI predicts high success if Alex practices 'Polynomials' before Friday's quiz.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 p-4 rounded-2xl bg-surface-container-low hover:bg-white border border-outline-variant/10 transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-orange-500 text-[20px] flex-shrink-0 mt-0.5">bedtime</span>
                        <div>
                            <p class="text-xs font-bold text-orange-600 mb-1">Sleep Routine</p>
                            <p class="text-xs text-on-surface-variant leading-relaxed">Late night activity detected on student portal. Ensure 8+ hours for optimal focus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Row -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Teacher Communication -->
            <div class="bg-white rounded-3xl p-6 shadow-soft border border-outline-variant/10">
                <h3 class="font-poppins font-bold text-on-surface text-base mb-4">Teacher Communication</h3>
                <div class="space-y-3">
                    @php
                        $messages = [
                            ['Ms. Elena Ro...', '10:30 AM', '"Alex participated excl...'],
                            ['Mr. David Smith', 'New', '"Please check the u...'],
                        ];
                    @endphp
                    @foreach($messages as [$teacher, $time, $preview])
                    <div class="flex items-start gap-3 p-3 rounded-2xl hover:bg-surface-container-low transition-colors cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-on-primary text-sm font-bold flex-shrink-0">
                            {{ strtoupper(substr($teacher, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold text-on-surface">{{ $teacher }}</p>
                                <span class="text-[10px] {{ $time === 'New' ? 'text-primary font-bold' : 'text-on-surface-variant' }}">{{ $time }}</span>
                            </div>
                            <p class="text-xs text-on-surface-variant truncate mt-0.5">{{ $preview }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="mt-4 w-full py-2.5 border border-outline-variant/50 rounded-xl text-sm font-semibold text-on-surface hover:bg-surface-container-low transition-colors">
                    New Message
                </button>
            </div>

            <!-- Exam Calendar -->
            <div class="bg-white rounded-3xl p-6 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-poppins font-bold text-on-surface text-base">Exam Calendar</h3>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 rounded-lg bg-surface-container-low flex items-center justify-center hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">chevron_left</span>
                        </button>
                        <button class="w-7 h-7 rounded-lg bg-surface-container-low flex items-center justify-center hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant">chevron_right</span>
                        </button>
                    </div>
                </div>
                <!-- Mini Calendar -->
                <div class="grid grid-cols-7 gap-1 text-center mb-3">
                    @foreach(['M','T','W','T','F','S','S'] as $d)
                    <span class="text-[10px] font-semibold text-on-surface-variant pb-1">{{ $d }}</span>
                    @endforeach
                    @for($i = 1; $i <= 28; $i++)
                    <button class="w-7 h-7 rounded-full text-xs flex items-center justify-center mx-auto
                        {{ $i === 5 ? 'bg-primary text-on-primary font-bold' : 'text-on-surface hover:bg-surface-container-low' }}
                        transition-colors">
                        {{ $i }}
                    </button>
                    @endfor
                </div>
                <!-- Upcoming Exam -->
                <div class="mt-3 p-4 rounded-2xl border-l-4 border-primary bg-primary/5">
                    <p class="text-xs font-bold text-primary">UPCOMING: DEC 5</p>
                    <p class="text-sm font-semibold text-on-surface mt-1">Term 1 Chemistry Finals</p>
                    <p class="text-xs text-on-surface-variant mt-0.5">Room 302 • 09:00 AM – 11:30 AM</p>
                </div>
            </div>

            <!-- Fee Payment + Report Cards -->
            <div class="flex flex-col gap-4">

                <!-- Fee Payment -->
                <div class="rounded-3xl p-6 text-on-primary shadow-lg"
                     style="background: linear-gradient(135deg, #3525cd, #4f46e5);">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-poppins font-bold">Fee Payment</h3>
                        <span class="material-symbols-outlined text-[20px] opacity-70">credit_card</span>
                    </div>
                    <p class="text-xs text-on-primary/70 mb-4">TERM 2 – DEC 2024</p>
                    <p class="text-xs text-on-primary/70 mb-1">Outstanding Balance</p>
                    <p class="font-poppins text-3xl font-bold">$1,240.00</p>
                    <button class="mt-5 w-full bg-white text-primary py-3 rounded-xl font-poppins font-semibold text-sm hover:shadow-xl transition-all hover:scale-[1.02]">
                        Quick Pay Now
                    </button>
                </div>

                <!-- Report Cards -->
                <div class="bg-white rounded-3xl p-6 shadow-soft border border-outline-variant/10 flex-1">
                    <h3 class="font-poppins font-bold text-on-surface text-base mb-4">Report Cards</h3>
                    <div class="space-y-2">
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-surface-container-low hover:bg-white transition-colors cursor-pointer group">
                            <span class="material-symbols-outlined text-primary text-[20px]">description</span>
                            <p class="flex-1 text-sm font-semibold text-on-surface">Oct 2024</p>
                            <span class="material-symbols-outlined text-on-surface-variant opacity-0 group-hover:opacity-100 transition-opacity text-[18px]">download</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-surface-container-low hover:bg-white transition-colors cursor-pointer group">
                            <span class="material-symbols-outlined text-secondary text-[20px]">description</span>
                            <p class="flex-1 text-sm font-semibold text-on-surface">Sep 2024</p>
                            <span class="material-symbols-outlined text-on-surface-variant opacity-0 group-hover:opacity-100 transition-opacity text-[18px]">download</span>
                        </div>
                    </div>
                    <button class="mt-4 w-full py-2.5 rounded-xl bg-secondary/10 text-secondary text-sm font-semibold hover:bg-secondary/20 transition-colors">
                        Full Progress History
                    </button>
                </div>
            </div>
        </section>

        <!-- School Notice -->
        <div class="bg-white rounded-3xl p-5 shadow-soft border border-outline-variant/10 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-error text-[20px]">campaign</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-on-surface">Urgent School Notice</p>
                <p class="text-xs text-on-surface-variant mt-0.5">The upcoming 'Annual Science Fair' has been rescheduled to January 15th, 2025. Please review the updated itinerary.</p>
            </div>
            <button class="flex-shrink-0 px-5 py-2.5 bg-primary text-on-primary rounded-xl text-sm font-semibold hover:shadow-md hover:-translate-y-0.5 transition-all">
                View Details
            </button>
        </div>

        <!-- Support Status -->
        <div class="fixed bottom-6 left-72 z-30">
            <div class="glass rounded-2xl px-4 py-3 flex items-center gap-2 shadow-lg border border-white/60">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <div>
                    <p class="text-xs font-bold text-on-surface">Support Status</p>
                    <p class="text-[10px] text-on-surface-variant">24/7 Parent Helpline</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
