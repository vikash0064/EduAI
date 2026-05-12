<x-app-layout>
    <x-slot name="pageTitle">Attendance: {{ $student->user->name }}</x-slot>

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Attendance Records</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Detailed daily attendance history for your child.</p>
            </div>
            <div class="flex gap-4">
                <div class="p-4 bg-white rounded-2xl shadow-sm border border-outline-variant/30 text-center">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase mb-1">Total Days</p>
                    <p class="text-xl font-bold text-on-surface">{{ $attendances->total() }}</p>
                </div>
                <div class="p-4 bg-teal-50 rounded-2xl shadow-sm border border-teal-100 text-center">
                    <p class="text-[10px] font-bold text-teal-600 uppercase mb-1">Present</p>
                    <p class="text-xl font-bold text-teal-600">{{ $student->attendances->where('status', 'present')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-soft border border-outline-variant/10 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-surface-container-low/30">
                    <tr>
                        <th class="px-8 py-5 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Date</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Day</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    @foreach($attendances as $attendance)
                    <tr class="hover:bg-surface-container-low/10 transition-colors">
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-on-surface">{{ $attendance->date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-on-surface-variant">{{ $attendance->date->format('l') }}</span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex justify-center">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $attendance->status === 'present' ? 'bg-teal-100 text-teal-700' : ($attendance->status === 'absent' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $attendance->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-xs font-bold text-on-surface-variant/60">—</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-8 border-t border-outline-variant/5">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
