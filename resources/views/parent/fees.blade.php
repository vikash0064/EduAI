<x-app-layout>
    <x-slot name="pageTitle">Fees: {{ $student->user->name }}</x-slot>

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Fee Management</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Monitor fee status, upcoming deadlines, and payment history.</p>
            </div>
            <div class="flex gap-4">
                <div class="p-6 bg-red-50 rounded-3xl border border-red-100 text-center">
                    <p class="text-[10px] font-bold text-red-600 uppercase mb-1">Total Outstanding</p>
                    <p class="text-2xl font-bold text-red-600">₹{{ number_format($fees->where('status', 'unpaid')->sum('amount')) }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @foreach($fees as $fee)
            <div class="bg-white rounded-[40px] p-8 shadow-soft border border-outline-variant/10 flex items-center justify-between group hover:border-primary/30 transition-all">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl {{ $fee->status === 'paid' ? 'bg-teal-50 text-teal-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">{{ $fee->status === 'paid' ? 'check_circle' : 'pending_actions' }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-poppins font-bold text-on-surface">{{ $fee->title }}</h3>
                        <p class="text-xs font-bold text-on-surface-variant mt-1">Due Date: {{ $fee->due_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-12">
                    <div class="text-right">
                        <p class="text-2xl font-poppins font-bold text-on-surface">₹{{ number_format($fee->amount) }}</p>
                        <span class="px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mt-2 inline-block
                            {{ $fee->status === 'paid' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($fee->status) }}
                        </span>
                    </div>
                    @if($fee->status !== 'paid')
                    <button class="px-8 py-3 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-105 transition-all">
                        Pay Now
                    </button>
                    @else
                    <button class="w-12 h-12 rounded-2xl bg-surface-container-low text-on-surface flex items-center justify-center hover:bg-primary hover:text-white transition-all">
                        <span class="material-symbols-outlined">download</span>
                    </button>
                    @endif
                </div>
            </div>
            @endforeach

            @if($fees->isEmpty())
            <div class="py-24 flex flex-col items-center justify-center text-center opacity-40 bg-white rounded-[40px] border border-outline-variant/10">
                <span class="material-symbols-outlined text-6xl mb-4">account_balance_wallet</span>
                <p class="text-lg font-bold">No fee records found.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
