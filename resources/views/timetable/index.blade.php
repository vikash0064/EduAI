<x-app-layout>
    <x-slot name="pageTitle">CSE Department Timetable</x-slot>
    
    <div class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Academic Schedule</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Computer Science Engineering • Semester Fall 2023</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-5 py-2.5 bg-white border border-outline-variant/30 rounded-xl text-xs font-bold shadow-sm hover:bg-surface-container-low transition-all">Print Schedule</button>
                <button class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-xs font-bold shadow-lg hover:shadow-primary/20 transition-all">Add Extra Session</button>
            </div>
        </div>

        <!-- Timetable Grid -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10 overflow-x-auto">
            <table class="w-full min-w-[1000px] border-collapse">
                <thead>
                    <tr>
                        <th class="p-4 bg-surface-container-low rounded-tl-2xl border-b border-outline-variant/10"></th>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <th class="p-4 bg-surface-container-low border-b border-outline-variant/10 text-xs font-bold text-on-surface uppercase tracking-widest text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @php
                        $slots = [
                            '09:00 AM - 10:30 AM',
                            '10:45 AM - 12:15 PM',
                            '12:15 PM - 01:30 PM' => 'LUNCH BREAK',
                            '01:30 PM - 03:00 PM',
                            '03:15 PM - 04:45 PM',
                            '04:45 PM - 05:00 PM' => 'BREAK'
                        ];

                        $schedule = [
                            'Monday' => [
                                '09:00 AM - 10:30 AM' => ['Data Structures', 'CSE-4A', 'Lab 101', 'bg-indigo-50 text-indigo-700'],
                                '10:45 AM - 12:15 PM' => ['Operating Systems', 'CSE-4B', 'LT-2', 'bg-teal-50 text-teal-700'],
                                '01:30 PM - 03:00 PM' => ['Database Systems', 'CSE-3A', 'Lab 204', 'bg-amber-50 text-amber-700'],
                                '03:15 PM - 04:45 PM' => ['Discrete Math', 'CSE-2C', 'Room 12', 'bg-cyan-50 text-cyan-700'],
                            ],
                            'Tuesday' => [
                                '09:00 AM - 10:30 AM' => ['Computer Networks', 'CSE-4C', 'Lab 302', 'bg-purple-50 text-purple-700'],
                                '10:45 AM - 12:15 PM' => ['Theory of Comp', 'CSE-3B', 'LT-1', 'bg-rose-50 text-rose-700'],
                                '01:30 PM - 03:00 PM' => ['Software Eng.', 'CSE-4A', 'Room 15', 'bg-blue-50 text-blue-700'],
                                '03:15 PM - 04:45 PM' => ['OOP (Java)', 'CSE-2A', 'Lab 104', 'bg-emerald-50 text-emerald-700'],
                            ],
                            'Wednesday' => [
                                '09:00 AM - 10:30 AM' => ['Machine Learning', 'CSE-4B', 'AI Lab', 'bg-indigo-600 text-white'],
                                '10:45 AM - 12:15 PM' => ['Microprocessors', 'CSE-3A', 'Hardware Lab', 'bg-orange-50 text-orange-700'],
                                '01:30 PM - 03:00 PM' => ['Web Tech', 'CSE-4C', 'Web Lab', 'bg-sky-50 text-sky-700'],
                                '03:15 PM - 04:45 PM' => ['Digital Logic', 'CSE-2B', 'LT-3', 'bg-violet-50 text-violet-700'],
                            ],
                            'Thursday' => [
                                '09:00 AM - 10:30 AM' => ['Comp. Graphics', 'CSE-3C', 'Lab-X', 'bg-pink-50 text-pink-700'],
                                '10:45 AM - 12:15 PM' => ['Compiler Design', 'CSE-4A', 'LT-2', 'bg-indigo-50 text-indigo-700'],
                                '01:30 PM - 03:00 PM' => ['Cloud Computing', 'CSE-3B', 'Cloud Lab', 'bg-cyan-50 text-cyan-700'],
                                '03:15 PM - 04:45 PM' => ['Cyber Security', 'CSE-4B', 'Security Lab', 'bg-error/10 text-error'],
                            ],
                            'Friday' => [
                                '09:00 AM - 10:30 AM' => ['AI Essentials', 'CSE-4C', 'AI Lab', 'bg-indigo-100 text-indigo-800'],
                                '10:45 AM - 12:15 PM' => ['Ethics in IT', 'CSE-2A', 'Seminar Hall', 'bg-surface-container text-on-surface'],
                                '01:30 PM - 03:00 PM' => ['Capstone Project', 'CSE-4B', 'Innovation Lab', 'bg-teal-600 text-white'],
                                '03:15 PM - 04:45 PM' => ['Project Review', 'CSE-4B', 'Room 101', 'bg-teal-50 text-teal-700'],
                            ],
                            'Saturday' => [
                                '09:00 AM - 10:30 AM' => ['Expert Talk', 'All', 'Auditorium', 'bg-primary text-on-primary'],
                                '10:45 AM - 12:15 PM' => ['Workshop', 'CSE-4A', 'Room 12', 'bg-primary/10 text-primary'],
                            ]
                        ];
                    @endphp

                    @foreach($slots as $time => $break)
                    <tr>
                        <td class="p-4 bg-surface-container-low border-r border-outline-variant/10 text-[10px] font-bold text-on-surface-variant uppercase text-center w-32">
                            {{ is_numeric($time) ? $break : $time }}
                        </td>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            @if(!is_numeric($time)) {{-- It's a Break Slot --}}
                                <td class="p-2 border border-outline-variant/10 bg-surface-container text-center">
                                    <span class="text-[9px] font-bold text-on-surface-variant opacity-40 tracking-[0.2em] uppercase">{{ $break }}</span>
                                </td>
                            @else
                                <td class="p-2 border border-outline-variant/10 min-w-[150px]">
                                    @if(isset($schedule[$day][$break]))
                                        @php $item = $schedule[$day][$break]; @endphp
                                        <div class="{{ $item[3] }} p-4 rounded-2xl shadow-sm h-full flex flex-col justify-between group hover:scale-[1.02] transition-transform cursor-pointer">
                                            <div>
                                                <p class="text-[11px] font-bold leading-tight">{{ $item[0] }}</p>
                                                <p class="text-[9px] font-semibold opacity-80 mt-1 uppercase">{{ $item[1] }}</p>
                                            </div>
                                            <div class="flex items-center gap-1 mt-4 opacity-70">
                                                <span class="material-symbols-outlined text-[12px]">location_on</span>
                                                <span class="text-[9px] font-bold">{{ $item[2] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Timeline Footer -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-600 rounded-[28px] p-6 text-on-primary flex items-center gap-5 shadow-lg">
                <span class="material-symbols-outlined text-[32px]">event_repeat</span>
                <div>
                    <h4 class="font-poppins font-bold">Lab Day</h4>
                    <p class="text-xs opacity-80 font-semibold">Every Monday & Friday in Lab 101</p>
                </div>
            </div>
            <div class="bg-white rounded-[28px] p-6 border border-outline-variant/10 flex items-center gap-5 shadow-soft">
                <span class="material-symbols-outlined text-primary text-[32px]">history_edu</span>
                <div>
                    <h4 class="font-poppins font-bold text-on-surface">Weekly Assessment</h4>
                    <p class="text-xs text-on-surface-variant font-semibold">Saturdays 10:45 AM</p>
                </div>
            </div>
            <div class="bg-white rounded-[28px] p-6 border border-outline-variant/10 flex items-center gap-5 shadow-soft">
                <span class="material-symbols-outlined text-teal-600 text-[32px]">science</span>
                <div>
                    <h4 class="font-poppins font-bold text-on-surface">Capstone Project</h4>
                    <p class="text-xs text-on-surface-variant font-semibold">Phase 2 Review started</p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
