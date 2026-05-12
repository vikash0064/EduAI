<x-app-layout>
    <x-slot name="pageTitle">My Attendance Records</x-slot>
    
    <div class="space-y-8 max-w-[1400px] mx-auto pb-24">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Attendance Tracking</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Detailed history and performance analytics</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="px-5 py-2.5 bg-white border border-outline-variant/30 rounded-xl text-xs font-bold shadow-sm hover:bg-surface-container-low transition-all">Download Report</button>
            </div>
        </div>

        <!-- Summary Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Attendance Overview Chart -->
            <div class="lg:col-span-8 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="font-poppins font-bold text-xl text-on-surface">Weekly Presence Trend</h2>
                        <p class="text-xs font-semibold text-on-surface-variant mt-1">Status fluctuations over the last 7 sessions</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-teal-500"></span>
                            <span class="text-[10px] font-bold text-on-surface-variant uppercase">Present</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="text-[10px] font-bold text-on-surface-variant uppercase">Late</span>
                        </div>
                    </div>
                </div>

                <div class="h-[300px] w-full relative">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Stats Ring -->
            <div class="lg:col-span-4 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10 flex flex-col items-center justify-center text-center">
                <div class="relative w-48 h-48 mb-6">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <path class="text-outline-variant/10" stroke-dasharray="100, 100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-teal-500" stroke-dasharray="{{ $percentage }}, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-poppins font-bold text-on-surface">{{ $percentage }}%</span>
                        <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-1">Average</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 w-full">
                    <div class="text-center">
                        <p class="text-xl font-bold text-teal-600">{{ $stats['present'] }}</p>
                        <p class="text-[9px] font-bold text-on-surface-variant uppercase">Present</p>
                    </div>
                    <div class="text-center border-x border-outline-variant/10">
                        <p class="text-xl font-bold text-amber-600">{{ $stats['late'] }}</p>
                        <p class="text-[9px] font-bold text-on-surface-variant uppercase">Late</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-bold text-error">{{ $stats['absent'] }}</p>
                        <p class="text-[9px] font-bold text-on-surface-variant uppercase">Absent</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
            <h2 class="font-poppins font-bold text-xl text-on-surface mb-8">Detailed History</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-outline-variant/10">
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Date</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Subject Reference</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($attendances as $att)
                        <tr class="group hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-surface-container-low flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ \Carbon\Carbon::parse($att->date)->format('M d, Y') }}</p>
                                        <p class="text-[10px] font-semibold text-on-surface-variant uppercase">{{ \Carbon\Carbon::parse($att->date)->format('l') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5">
                                <span class="px-4 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider
                                    {{ $att->status === 'present' ? 'bg-teal-100 text-teal-700' : 
                                       ($att->status === 'absent' ? 'bg-error/10 text-error' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $att->status }}
                                </span>
                            </td>
                            <td class="py-5">
                                <p class="text-xs font-bold text-on-surface">Scheduled Session</p>
                                <p class="text-[10px] font-semibold text-on-surface-variant mt-0.5 uppercase">CSE Core Subject</p>
                            </td>
                            <td class="py-5 text-right">
                                <div class="flex items-center justify-end gap-2 text-teal-600">
                                    <span class="material-symbols-outlined text-[18px]">verified</span>
                                    <span class="text-[10px] font-bold uppercase">Teacher Verified</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Chart Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData->pluck('day')) !!},
                    datasets: [{
                        label: 'Presence Level',
                        data: {!! json_encode($chartData->pluck('val')) !!},
                        borderColor: '#4f46e5',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 11 },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    let val = context.parsed.y;
                                    if (val === 100) return 'Status: Present';
                                    if (val === 50) return 'Status: Late';
                                    return 'Status: Absent';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    if (value === 100) return 'Present';
                                    if (value === 50) return 'Late';
                                    if (value === 0) return 'Absent';
                                    return '';
                                },
                                font: { size: 10, weight: 'bold' }
                            },
                            grid: { color: 'rgba(0,0,0,0.03)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10, weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
