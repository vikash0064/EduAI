<x-app-layout>
    <x-slot name="pageTitle">Results: {{ $student->user->name }}</x-slot>

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Academic Performance</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Detailed breakdown of exam results and subject performance.</p>
            </div>
            <button class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:scale-105 transition-all">
                <span class="material-symbols-outlined text-[20px]">file_download</span>
                Full Marksheet PDF
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($results->groupBy('subject_id') as $subjectResults)
                @php $subject = $subjectResults->first()->subject; @endphp
                <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">menu_book</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-poppins font-bold text-on-surface">{{ $subject->name }}</h3>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">Avg: {{ round($subjectResults->avg('score')) }}%</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($subjectResults as $result)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-surface-container-low/50">
                            <div>
                                <p class="text-xs font-bold text-on-surface">{{ $result->exam_type }}</p>
                                <p class="text-[10px] font-bold text-on-surface-variant">{{ $result->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-on-surface">{{ $result->score }}/{{ $result->max_score }}</p>
                                @php $pct = ($result->score / $result->max_score) * 100; @endphp
                                <span class="text-[9px] font-bold {{ $pct >= 80 ? 'text-teal-600' : ($pct >= 60 ? 'text-amber-600' : 'text-red-600') }}">{{ round($pct) }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
