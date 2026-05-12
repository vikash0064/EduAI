<x-app-layout>
    <x-slot name="pageTitle">Fee Management (Admin)</x-slot>

    <div x-data="{ openModal: false }" class="space-y-8 max-w-[1400px] mx-auto">
        
        <!-- Alerts -->
        @if(session('success'))
            <div class="bg-success/10 border border-success/20 text-success p-4 rounded-2xl flex items-center gap-3 animate-fade-in">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-poppins text-3xl font-bold text-on-surface tracking-tight">Fee Administration</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-1">Manage school billing, payment records, and financial status.</p>
            </div>
            <button @click="openModal = true" class="flex items-center gap-2 px-6 py-3 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Create Fee
            </button>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-[24px] p-8 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-32 h-32 bg-success/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Total Collected</p>
                <h3 class="text-4xl font-poppins font-black text-success">₹{{ number_format($totalCollected) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-xs font-bold text-success bg-success/5 w-fit px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    +12% from last month
                </div>
            </div>
            <div class="bg-white rounded-[24px] p-8 shadow-soft border border-outline-variant/10 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-32 h-32 bg-error/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-2">Total Outstanding</p>
                <h3 class="text-4xl font-poppins font-black text-error">₹{{ number_format($totalOutstanding) }}</h3>
                <div class="mt-4 flex items-center gap-2 text-xs font-bold text-error bg-error/5 w-fit px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[14px]">warning</span>
                    Needs attention
                </div>
            </div>
        </div>

        <!-- Fees Table -->
        <div class="bg-white rounded-[32px] p-8 shadow-soft border border-outline-variant/10">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-outline-variant/10">
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Student</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Fee Title</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Amount</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Due Date</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Status</th>
                            <th class="pb-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @foreach($fees as $fee)
                        <tr class="group hover:bg-surface-container-low/30 transition-colors">
                            <td class="py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-surface-container-low flex items-center justify-center text-on-surface font-bold text-xs">
                                        {{ strtoupper(substr($fee->studentProfile->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $fee->studentProfile->user->name }}</p>
                                        <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-tighter">Class {{ $fee->studentProfile->grade_level }}{{ $fee->studentProfile->section }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5">
                                <p class="text-sm font-semibold text-on-surface">{{ $fee->title }}</p>
                            </td>
                            <td class="py-5">
                                <p class="text-sm font-black text-on-surface">₹{{ number_format($fee->amount) }}</p>
                            </td>
                            <td class="py-5 text-sm font-medium text-on-surface-variant">
                                {{ $fee->due_date->format('M d, Y') }}
                            </td>
                            <td class="py-5">
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider
                                    {{ $fee->status === 'paid' ? 'bg-success/10 text-success' : 'bg-error/10 text-error animate-pulse' }}">
                                    {{ $fee->status }}
                                </span>
                            </td>
                            <td class="py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.fees.edit', $fee) }}" class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-white hover:shadow-md transition-all">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.fees.destroy', $fee) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant hover:bg-error/10 hover:text-error transition-all">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $fees->links() }}
            </div>
        </div>

        <!-- Create Fee Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div @click="openModal = false" class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-[32px] shadow-2xl w-full max-w-xl overflow-hidden animate-fade-in" @click.away="openModal = false">
                <div class="p-8 border-b border-outline-variant/10">
                    <h2 class="text-2xl font-poppins font-bold text-on-surface">Generate New Billing</h2>
                    <p class="text-sm text-on-surface-variant mt-1">Assign a new fee to a specific student.</p>
                </div>
                
                <form action="{{ route('admin.fees.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Select Student</label>
                            <select name="student_profile_id" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                                <option value="">Choose a student...</option>
                                @foreach(\App\Models\StudentProfile::with('user')->get() as $student)
                                    <option value="{{ $student->id }}">{{ $student->user->name }} (Grade {{ $student->grade_level }}{{ $student->section }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Fee Title</label>
                            <input type="text" name="title" required placeholder="e.g. Term 2 Tuition Fee" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Amount (₹)</label>
                            <input type="number" name="amount" required placeholder="0.00" class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm font-black">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Due Date</label>
                            <input type="date" name="due_date" required class="w-full px-5 py-3 bg-surface-container-low border-none rounded-2xl text-sm">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-on-surface-variant uppercase mb-2">Initial Status</label>
                            <div class="flex items-center gap-3 bg-surface-container-low p-1.5 rounded-2xl">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="unpaid" checked class="hidden peer">
                                    <div class="py-2.5 rounded-xl text-center text-xs font-bold peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-error text-on-surface-variant transition-all">Unpaid</div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="paid" class="hidden peer">
                                    <div class="py-2.5 rounded-xl text-center text-xs font-bold peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-success text-on-surface-variant transition-all">Paid</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <button type="button" @click="openModal = false" class="flex-1 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-low rounded-2xl transition-all">Cancel</button>
                        <button type="submit" class="flex-[2] py-4 bg-primary text-on-primary rounded-2xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">Confirm Billing</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
