<x-app-layout>
    <x-slot name="pageTitle">Institutional Overview</x-slot>
    <x-slot name="styles">
        <style>
            .shadow-soft {
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            }
        </style>
    </x-slot>

    <div class="min-h-screen bg-[#F8F9FD] p-6 lg:p-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#1B1B24] mb-1">Institutional Overview</h1>
                <p class="text-[#6B7280] text-sm">Real-time academic health and operational intelligence.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-cyan-50 text-cyan-600 text-[11px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span>
                    Live Data
                </span>
                <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-outline-variant/10 shadow-sm text-sm font-semibold text-[#374151]">
                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    Academic Year 2024
                </button>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-primary border border-outline-variant/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </div>
                    <span class="text-[10px] font-bold text-success flex items-center gap-1 bg-success/5 px-2 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[12px]">trending_up</span>
                        +12.5%
                    </span>
                </div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Total Students</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format($stats['total_students']) }}</h3>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-teal-500 border border-outline-variant/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <span class="material-symbols-outlined text-[20px]">badge</span>
                    </div>
                    <span class="text-[10px] font-bold text-on-surface-variant flex items-center gap-1 bg-surface-container-low px-2 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[12px]">horizontal_rule</span>
                        Stable
                    </span>
                </div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Active Staff</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ number_format($stats['total_teachers']) }}</h3>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-amber-500 border border-outline-variant/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                    </div>
                    <span class="text-[10px] font-bold text-error flex items-center gap-1 bg-error/5 px-2 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[12px]">trending_up</span>
                        +8.2%
                    </span>
                </div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Outstanding Fees</p>
                <h3 class="text-3xl font-poppins font-bold text-on-surface">₹{{ number_format($stats['outstanding_fees']) }}</h3>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-white rounded-[24px] p-6 shadow-soft border-l-[4px] border-l-indigo-500 border border-outline-variant/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <span class="material-symbols-outlined text-[20px]">verified_user</span>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 flex items-center gap-1 bg-indigo-50 px-2 py-1 rounded-full">
                        Elite
                    </span>
                </div>
                <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest mb-1">Academic Health</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-poppins font-bold text-on-surface">{{ $stats['academic_health'] }}%</h3>
                    <span class="material-symbols-outlined text-amber-500 text-[16px] animate-pulse">auto_awesome</span>
                </div>
            </div>
        </div>

        <!-- Trends & Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Enrollment Trends Chart -->
            <div x-data="{ trendType: 'monthly' }" class="lg:col-span-2 bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-lg font-bold text-on-surface">Enrollment Trends</h3>
                        <p class="text-xs text-on-surface-variant">Comparative analysis across campuses</p>
                    </div>
                    <div class="flex items-center gap-2 bg-surface-container-low p-1 rounded-xl">
                        <button @click="trendType = 'monthly'" :class="trendType === 'monthly' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all">Monthly</button>
                        <button @click="trendType = 'yearly'" :class="trendType === 'yearly' ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant'" class="px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all">Yearly</button>
                    </div>
                </div>
                
                <div class="h-64 flex items-end justify-between gap-4 px-4">
                    <!-- Monthly Trends -->
                    <template x-if="trendType === 'monthly'">
                        <div class="flex items-end justify-between gap-4 w-full h-full">
                            @foreach($stats['enrollment_trends']['monthly'] as $trend)
                            <div class="flex-1 flex flex-col items-center gap-3 group h-full justify-end">
                                <div class="w-full bg-surface-container-low rounded-full relative overflow-hidden flex flex-col justify-end h-full">
                                    @php 
                                        $max = collect($stats['enrollment_trends']['monthly'])->max('count') ?: 1;
                                        $height = ($trend['count'] / $max) * 100;
                                        $height = max(5, $height); // Min height for visibility
                                    @endphp
                                    <div class="bg-primary/20 absolute inset-0 group-hover:bg-primary/30 transition-all"></div>
                                    <div class="bg-primary rounded-full transition-all duration-700" style="height: {{ $height }}%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">{{ $trend['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </template>

                    <!-- Yearly Trends -->
                    <template x-if="trendType === 'yearly'">
                        <div class="flex items-end justify-between gap-4 w-full h-full">
                            @foreach($stats['enrollment_trends']['yearly'] as $trend)
                            <div class="flex-1 flex flex-col items-center gap-3 group h-full justify-end">
                                <div class="w-full bg-surface-container-low rounded-full relative overflow-hidden flex flex-col justify-end h-full">
                                    @php 
                                        $max = collect($stats['enrollment_trends']['yearly'])->max('count') ?: 1;
                                        $height = ($trend['count'] / $max) * 100;
                                        $height = max(5, $height);
                                    @endphp
                                    <div class="bg-primary/20 absolute inset-0 group-hover:bg-primary/30 transition-all"></div>
                                    <div class="bg-primary rounded-full transition-all duration-700" style="height: {{ $height }}%;"></div>
                                </div>
                                <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">{{ $trend['label'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>

            <!-- Department Metrics -->
            <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
                <h3 class="text-lg font-bold text-on-surface mb-6">Department Metrics</h3>
                <div class="space-y-6">
                    @foreach($departmentMetrics as $dept)
                    <div class="flex items-center justify-between p-4 rounded-[24px] bg-surface-container-low border border-outline-variant/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">
                                    {{ str_contains(strtolower($dept['name']), 'math') ? 'functions' : 
                                       (str_contains(strtolower($dept['name']), 'science') ? 'science' : 'menu_book') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-on-surface">{{ $dept['name'] }}</p>
                                <p class="text-[10px] font-bold text-on-surface-variant">{{ $dept['avg'] }}% Performance</p>
                            </div>
                        </div>
                        <span class="text-lg font-poppins font-black text-primary">{{ $dept['grade'] }}</span>
                    </div>
                    @endforeach

                    <div class="p-6 bg-primary/5 rounded-[24px] mt-8">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="material-symbols-outlined text-primary text-[18px]">auto_awesome</span>
                            <h4 class="text-xs font-bold text-primary">Predictive Analysis</h4>
                        </div>
                        <p class="text-[11px] leading-relaxed text-on-surface-variant font-medium">
                            AI models predict a 15% increase in STEM student density. Recommendation: Provision 3 additional Physics labs.
                        </p>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-bold text-on-surface-variant uppercase">Confidence Score</span>
                                <span class="text-sm font-bold text-on-surface">92%</span>
                            </div>
                            <button class="text-[10px] font-bold text-primary hover:underline underline-offset-4">View Strategic Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty Allocation -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10 mb-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Faculty Resource Allocation</h3>
                    <p class="text-xs text-on-surface-variant">Real-time utilization and operational status</p>
                </div>
                <button class="px-5 py-2.5 rounded-xl border border-outline-variant/30 text-xs font-bold text-on-surface-variant hover:bg-surface-container-low transition-all">View All Staff</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-outline-variant/10">
                            <th class="pb-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Faculty Member</th>
                            <th class="pb-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Department</th>
                            <th class="pb-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Utilization</th>
                            <th class="pb-4 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($faculty as $member)
                        <tr class="group hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary font-bold text-xs">
                                        {{ $member['initials'] }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-on-surface">{{ $member['name'] }}</p>
                                        <p class="text-[10px] text-on-surface-variant font-semibold">Senior Lecturer</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 text-xs font-bold text-on-surface-variant">{{ $member['department'] }}</td>
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 h-1.5 bg-surface-container-low rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full" style="width: {{ $member['utilization'] }}%;"></div>
                                    </div>
                                    <span class="text-[10px] font-bold text-on-surface">{{ $member['utilization'] }}%</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-lg bg-success/10 text-success text-[10px] font-bold">{{ $member['status'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Infrastructure Monitoring --}}
            <div class="grid grid-cols-2 gap-4 col-span-3">
                <div class="bg-white rounded-3xl p-5 shadow-soft border border-outline-variant/5">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-[20px]">wifi</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#9CA3AF] uppercase">Network Health</p>
                    <h5 class="text-lg font-bold text-[#1B1B24]">99.9%</h5>
                    <p class="text-[10px] text-success font-bold mt-1">Uptime</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-soft border border-outline-variant/5">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-[20px]">bolt</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#9CA3AF] uppercase">Energy Usage</p>
                    <h5 class="text-lg font-bold text-[#1B1B24]">-12%</h5>
                    <p class="text-[10px] text-indigo-600 font-bold mt-1">Optimization</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-soft border border-outline-variant/5">
                    <div class="w-10 h-10 rounded-2xl bg-error/10 text-error flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-[20px]">warning</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#9CA3AF] uppercase">Maintenance</p>
                    <h5 class="text-lg font-bold text-[#1B1B24]">2 Priority</h5>
                    <p class="text-[10px] text-error font-bold mt-1">Alerts</p>
                </div>
                <div class="bg-white rounded-3xl p-5 shadow-soft border border-outline-variant/5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-[20px]">menu_book</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#9CA3AF] uppercase">Library Assets</p>
                    <h5 class="text-lg font-bold text-[#1B1B24]">84%</h5>
                    <p class="text-[10px] text-amber-600 font-bold mt-1">Circulation</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
