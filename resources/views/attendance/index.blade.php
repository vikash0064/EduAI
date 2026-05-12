<x-app-layout>
    <x-slot name="pageTitle">Attendance Management</x-slot>
    <x-slot name="searchPlaceholder">Search student by name or ID...</x-slot>

    <div x-data="attendanceManager()" x-init="init()" class="space-y-8 max-w-[1400px] mx-auto pb-24">

        <!-- Breadcrumbs and Header -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant mb-2">
                    <span x-text="currentClass.split('-')[0] || 'CSE'">CSE</span>
                </nav>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">
                    Attendance: <span x-text="currentClass">CSE</span>
                </h1>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Class Selector -->
                <div class="relative">
                    <select x-model="currentClass" class="pl-4 pr-10 py-3 bg-white border border-outline-variant/30 rounded-2xl text-sm font-bold text-on-surface shadow-sm focus:ring-2 focus:ring-primary/20 outline-none appearance-none cursor-pointer">
                        @foreach($todaySchedule as $session)
                            <option value="{{ $session['class'] }}">{{ $session['class'] }} ({{ $session['subject'] }})</option>
                        @endforeach
                        <option value="All Classes">All Classes</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                </div>

                <div class="flex items-center bg-white border border-outline-variant/30 rounded-2xl p-1.5 shadow-sm">
                    <button class="w-10 h-10 flex items-center justify-center hover:bg-surface-container-low rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                    </button>
                    <div class="px-4 flex items-center gap-2 border-x border-outline-variant/20">
                        <span class="material-symbols-outlined text-primary text-[20px]">calendar_today</span>
                        <span class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</span>
                    </div>
                    <button class="w-10 h-10 flex items-center justify-center hover:bg-surface-container-low rounded-xl transition-colors">
                        <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        @if($currentSession)
        <!-- Live Session Alert -->
        <div class="bg-primary/5 border border-primary/10 rounded-[24px] p-4 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined animate-pulse">radio_button_checked</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest">Ongoing Session</p>
                    <p class="text-sm font-bold text-on-surface">{{ $currentSession['subject'] }} • {{ $currentSession['time'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-outline-variant/20">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-on-surface-variant uppercase">Taking Attendance</span>
            </div>
        </div>
        @endif

        <!-- Summary Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-primary border border-outline-variant/10">
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Class Strength</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-on-surface">{{ $classStrength }}</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-teal-500 border border-outline-variant/10">
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Present</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-teal-600" x-text="counts.present">0</span>
                    <span class="text-[10px] font-bold text-teal-500 uppercase ml-1" x-text="Math.round((counts.present / {{ $classStrength ?: 1 }}) * 100) + '%'">0%</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-error border border-outline-variant/10">
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Absent</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-error" x-text="counts.absent">0</span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-amber-500 border border-outline-variant/10">
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1">Late</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-poppins font-bold text-amber-600" x-text="counts.late">0</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Student Roll Call Section -->
            <div class="lg:col-span-8 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-poppins font-bold text-xl text-on-surface">Daily Attendance Register</h2>
                    <div class="flex items-center gap-3">
                        <button @click="markAll('present')" class="flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-xl text-xs font-bold hover:bg-primary hover:text-on-primary transition-all">
                            <span class="material-symbols-outlined text-[18px]">done_all</span>
                            Mark All Present
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-outline-variant/10">
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Student</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Status</th>
                                <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/10">
                            @foreach($students as $student)
                            <tr class="group" x-show="currentClass === 'All Classes' || currentClass === '{{ $student->grade_level }}-{{ $student->section }}'">
                                <td class="py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-surface-container-low flex items-center justify-center text-primary font-bold overflow-hidden shadow-sm">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->user->name) }}&background=f0f0f0&color=4f46e5" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-on-surface">{{ $student->user->name }}</p>
                                            <p class="text-[11px] font-semibold text-on-surface-variant uppercase">ID: {{ $student->enrollment_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <div class="flex items-center justify-center bg-surface-container-low rounded-2xl p-1.5 w-fit mx-auto border border-outline-variant/10">
                                        <button @click="markStatus({{ $student->id }}, 'present')" 
                                                :class="attendanceData[{{ $student->id }}] === 'present' ? 'bg-teal-500 text-white shadow-md' : 'text-on-surface-variant hover:bg-white'"
                                                class="px-4 py-1.5 rounded-xl text-[11px] font-bold transition-all">
                                            Present
                                        </button>
                                        <button @click="markStatus({{ $student->id }}, 'absent')" 
                                                :class="attendanceData[{{ $student->id }}] === 'absent' ? 'bg-error text-white shadow-md' : 'text-on-surface-variant hover:bg-white'"
                                                class="px-4 py-1.5 rounded-xl text-[11px] font-bold transition-all">
                                            Absent
                                        </button>
                                        <button @click="markStatus({{ $student->id }}, 'late')" 
                                                :class="attendanceData[{{ $student->id }}] === 'late' ? 'bg-amber-500 text-white shadow-md' : 'text-on-surface-variant hover:bg-white'"
                                                class="px-4 py-1.5 rounded-xl text-[11px] font-bold transition-all">
                                            Late
                                        </button>
                                    </div>
                                </td>
                                <td class="py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div x-show="syncingIds.includes({{ $student->id }})" class="w-4 h-4 border-2 border-primary/30 border-t-primary rounded-full animate-spin"></div>
                                        <span x-show="!syncingIds.includes({{ $student->id }})" class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">
                                            <span x-text="attendanceData[{{ $student->id }}] ? 'Saved' : 'Pending'"></span>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Sidebar Panel -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Session Details -->
                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10">
                    <h3 class="font-poppins font-bold text-on-surface mb-6">Session Details</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10">
                            <span class="text-xs font-bold text-on-surface-variant">Class</span>
                            <span class="text-xs font-bold text-on-surface" x-text="currentClass">CSE</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10">
                            <span class="text-xs font-bold text-on-surface-variant">Subject</span>
                            <span class="text-xs font-bold text-on-surface">{{ $currentSession['subject'] ?? 'Not Specified' }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10">
                            <span class="text-xs font-bold text-on-surface-variant">Time</span>
                            <span class="text-xs font-bold text-on-surface">{{ $currentSession['time'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- AI Predictive Insights -->
                <div class="bg-indigo-600 rounded-[32px] p-6 shadow-soft text-on-primary">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-[20px]">psychology</span>
                        <h3 class="font-poppins font-bold text-[14px]">EduAI Insight</h3>
                    </div>
                    <p class="text-xs leading-relaxed opacity-90">
                        Attendance is slightly lower than usual for this time. AI suggests sending automated reminders to the 3 students who are often late.
                    </p>
                    <button class="mt-4 w-full py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl text-[10px] font-bold transition-all border border-white/30">Send Reminders</button>
                </div>
            </div>
        </div>

        <!-- Floating Status Bar -->
        <div x-show="unsavedCount > 0" x-transition class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50">
            <div class="bg-white/80 backdrop-blur-md border border-outline-variant/30 px-6 py-3 rounded-full shadow-2xl flex items-center gap-4">
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full bg-teal-600 border-2 border-white flex items-center justify-center text-[8px] text-white font-bold" x-text="counts.present"></div>
                    <div class="w-6 h-6 rounded-full bg-error border-2 border-white flex items-center justify-center text-[8px] text-white font-bold" x-text="counts.absent"></div>
                    <div class="w-6 h-6 rounded-full bg-amber-500 border-2 border-white flex items-center justify-center text-[8px] text-white font-bold" x-text="counts.late"></div>
                </div>
                <div class="h-4 w-[1px] bg-outline-variant/30"></div>
                <p class="text-xs font-bold text-on-surface"><span x-text="unsavedCount"></span> new changes synced</p>
                <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
            </div>
        </div>

    </div>

    <!-- Attendance Logic -->
    <script>
        function attendanceManager() {
            return {
                date: '{{ $date }}',
                currentClass: '{{ $currentSession['class'] ?? 'CSE' }}',
                attendanceData: {},
                syncingIds: [],
                unsavedCount: 0,
                counts: { present: 0, absent: 0, late: 0 },
                
                init() {
                    // Populate initial data from the backend
                    const initialData = {
                        @foreach($students as $student)
                            @php $att = $student->attendances->first(); @endphp
                            @if($att)
                                '{{ $student->id }}': '{{ $att->status }}',
                            @endif
                        @endforeach
                    };
                    this.attendanceData = initialData;
                    this.updateCounts();
                },

                updateCounts() {
                    let p = 0, a = 0, l = 0;
                    Object.values(this.attendanceData).forEach(status => {
                        if (status === 'present') p++;
                        if (status === 'absent') a++;
                        if (status === 'late') l++;
                    });
                    this.counts = { present: p, absent: a, late: l };
                },

                markStatus(id, status) {
                    if (this.attendanceData[id] === status) return;
                    
                    const oldStatus = this.attendanceData[id];
                    this.attendanceData[id] = status;
                    this.syncingIds.push(id);
                    
                    // Real-time AJAX call
                    fetch('{{ route('teacher.attendance.mark-ajax') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            student_id: id,
                            status: status,
                            date: this.date
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            this.unsavedCount++;
                            this.updateCounts();
                        }
                    })
                    .catch(err => console.error('Sync failed:', err))
                    .finally(() => {
                        this.syncingIds = this.syncingIds.filter(sid => sid !== id);
                    });
                },

                markAll(status) {
                    @foreach($students as $student)
                        this.markStatus({{ $student->id }}, status);
                    @endforeach
                }
            }
        }
    </script>
</x-app-layout>
