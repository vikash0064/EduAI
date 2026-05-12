<x-app-layout>
    <x-slot name="pageTitle">Edit Fee Record</x-slot>

    <div class="max-w-2xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.fees.index') }}" class="w-12 h-12 rounded-2xl bg-white shadow-soft flex items-center justify-center text-on-surface-variant hover:text-primary transition-all">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="font-poppins text-2xl font-bold text-on-surface tracking-tight">Edit Billing Details</h1>
                <p class="text-sm font-semibold text-on-surface-variant mt-0.5">Modify record for {{ $fee->studentProfile->user->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[40px] p-10 shadow-soft border border-outline-variant/10 overflow-hidden relative">
            <div class="absolute right-0 top-0 w-48 h-48 bg-primary/5 rounded-bl-[100px] -mr-16 -mt-16"></div>
            
            <form action="{{ route('admin.fees.update', $fee) }}" method="POST" class="relative space-y-8">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Target Student</label>
                        <select name="student_profile_id" required class="w-full px-6 py-4 bg-surface-container-low border-none rounded-3xl text-sm font-bold focus:ring-2 focus:ring-primary/20">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ $fee->student_profile_id === $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} (Grade {{ $student->grade_level }}{{ $student->section }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Billing Title</label>
                        <input type="text" name="title" value="{{ $fee->title }}" required class="w-full px-6 py-4 bg-surface-container-low border-none rounded-3xl text-sm font-bold focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Amount (₹)</label>
                            <input type="number" name="amount" value="{{ $fee->amount }}" required class="w-full px-6 py-4 bg-surface-container-low border-none rounded-3xl text-sm font-black focus:ring-2 focus:ring-primary/20 text-primary">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Due Date</label>
                            <input type="date" name="due_date" value="{{ $fee->due_date->format('Y-m-d') }}" required class="w-full px-6 py-4 bg-surface-container-low border-none rounded-3xl text-sm font-bold focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-on-surface-variant uppercase tracking-[0.2em] mb-3">Payment Status</label>
                        <div class="flex items-center gap-3 bg-surface-container-low p-2 rounded-[24px]">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status" value="unpaid" {{ $fee->status === 'unpaid' ? 'checked' : '' }} class="hidden peer">
                                <div class="py-3.5 rounded-2xl text-center text-xs font-black uppercase tracking-widest peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-error text-on-surface-variant transition-all">Unpaid</div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="status" value="paid" {{ $fee->status === 'paid' ? 'checked' : '' }} class="hidden peer">
                                <div class="py-3.5 rounded-2xl text-center text-xs font-black uppercase tracking-widest peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-success text-on-surface-variant transition-all">Paid</div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center gap-4">
                    <button type="submit" class="flex-1 py-5 bg-primary text-on-primary rounded-[28px] text-sm font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:shadow-primary/30 hover:-translate-y-1 transition-all">
                        Update Record
                    </button>
                    <a href="{{ route('admin.fees.index') }}" class="px-8 py-5 text-sm font-bold text-on-surface-variant hover:text-on-surface transition-all">
                        Discard
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
