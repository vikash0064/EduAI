<x-app-layout>
    <x-slot name="pageTitle">Teacher Dashboard</x-slot>
    <x-slot name="searchPlaceholder">Search students or performance reports...</x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Welcome Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Academic Overview</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Real-time performance monitoring for CSE</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-outline-variant/30 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold">88%</div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface-variant uppercase">Avg Attendance</p>
                        <p class="text-xs font-bold text-teal-600">+2.4% this week</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left: Main Content (Students Table) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- AI Predictive Insights: Class Performance Flow -->
                <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[20px]">insights</span>
                            </div>
                            <div>
                                <h2 class="font-poppins font-bold text-xl text-on-surface">Class Performance Flow</h2>
                                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mt-0.5">AI Predictive Analysis</p>
                            </div>
                        </div>
                        
                        <!-- Toggle Buttons -->
                        <div x-data="{ view: 'weekly' }" class="flex items-center gap-1 bg-surface-container-low p-1 rounded-xl">
                            <button @click="view = 'weekly'; updateChart('weekly')" 
                                    :class="view === 'weekly' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'"
                                    class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                                Weekly
                            </button>
                            <button @click="view = 'monthly'; updateChart('monthly')" 
                                    :class="view === 'monthly' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'"
                                    class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all">
                                Monthly
                            </button>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="h-[250px] w-full">
                        <canvas id="performanceFlowChart"></canvas>
                    </div>

                    <!-- Prediction Footer -->
                    <div class="mt-8 p-4 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-indigo-600 text-[20px]">psychology</span>
                            <p class="text-xs font-bold text-indigo-900">AI Prediction: <span class="font-normal opacity-80">Expect a 5% increase in grades next week based on current submission trends.</span></p>
                        </div>
                        <span class="text-[10px] font-bold text-indigo-600 bg-white px-3 py-1 rounded-full">94% Confidence</span>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                    <h3 class="font-poppins font-bold text-xl text-on-surface mb-6">Student Roster</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-outline-variant/10">
                                    <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Student</th>
                                    <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Grade</th>
                                    <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                                    <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Performance</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/10">
                                @foreach($students as $student)
                                <tr class="group hover:bg-surface-container-low/30 transition-colors">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center font-bold text-primary">
                                                {{ substr($student->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-on-surface">{{ $student->user->name }}</p>
                                                <p class="text-[10px] font-bold text-on-surface-variant">ID: {{ $student->enrollment_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 text-xs font-bold text-on-surface">Grade {{ $student->grade_level }}-{{ $student->section }}</td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-[10px] font-bold">Active</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600">
                                                <span class="text-xs font-bold">A+</span>
                                                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                                            </div>
                                            <a href="{{ route('teacher.students.show', $student) }}" class="p-2 rounded-xl bg-surface-container hover:bg-primary hover:text-white transition-all group/btn" title="View Full Profile">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Today's Schedule -->
                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10">
                    <h3 class="font-poppins font-bold text-on-surface mb-6">Today's Schedule</h3>
                    <div class="space-y-4">
                        @foreach([['Math', '08:00 AM', 'Room 101'], ['Physics', '10:30 AM', 'Room 302']] as [$sub, $time, $rm])
                        <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/10 group hover:border-primary/30 transition-all">
                            <p class="text-xs font-bold text-on-surface">{{ $sub }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">schedule</span> {{ $time }}
                                </span>
                                <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">location_on</span> {{ $rm }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- AI Top Performers -->
                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-outline-variant/10">
                    <h3 class="font-poppins font-bold text-on-surface mb-6">AI Top Performers</h3>
                    <div class="space-y-4">
                        @foreach(['Alex Johnson', 'Elena Martinez', 'Liam Hudson'] as $name)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-[10px] font-bold text-primary">
                                    {{ substr($name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-on-surface">{{ $name }}</span>
                            </div>
                            <span class="text-[10px] font-bold text-teal-600 bg-teal-50 px-2 py-1 rounded-lg">98%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart Logic -->
    <script>
        let chart;
        const ctx = document.getElementById('performanceFlowChart').getContext('2d');
        
        const dataSet = {
            weekly: @json($performanceData['weekly']),
            monthly: @json($performanceData['monthly'])
        };

        const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        function initChart() {
            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Performance %',
                        data: dataSet.weekly,
                        borderColor: '#4f46e5',
                        backgroundColor: (context) => {
                            const bgColor = [
                                'rgba(79, 70, 229, 0.2)',
                                'rgba(79, 70, 229, 0)'
                            ];
                            if (!context.chart.chartArea) return;
                            const { ctx, chartArea: { top, bottom } } = context.chart;
                            const gradient = ctx.createLinearGradient(0, top, 0, bottom);
                            gradient.addColorStop(0, bgColor[0]);
                            gradient.addColorStop(1, bgColor[1]);
                            return gradient;
                        },
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#4f46e5',
                        pointHoverBorderColor: '#fff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: (context) => `${context.parsed.y}% Proficiency`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 10, weight: '600' }, color: '#64748b' }
                        },
                        y: {
                            min: 0,
                            max: 100,
                            ticks: {
                                stepSize: 25,
                                font: { family: 'Inter', size: 10, weight: '600' },
                                color: '#64748b',
                                callback: (value) => value + '%'
                            },
                            grid: { color: 'rgba(0,0,0,0.03)' }
                        }
                    }
                }
            });
        }

        window.updateChart = function(view) {
            chart.data.datasets[0].data = dataSet[view];
            chart.update('active');
        };

        document.addEventListener('DOMContentLoaded', initChart);
    </script>
</x-app-layout>
