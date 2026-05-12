<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report for: ') }} {{ $student->user->name ?? 'Unknown' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold">Academic Progress Report</h3>
                    <p class="text-gray-600">Generated: {{ \Carbon\Carbon::now()->format('M d, Y') }}</p>
                </div>
                <div>
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700" onclick="window.print()">Print Report</button>
                </div>
            </div>
            
            <!-- Rest of the report content would be populated based on the controller data -->
            <div class="bg-white shadow sm:rounded-lg p-6">
                <p class="text-gray-500">Detailed report content goes here.</p>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.reports.index') }}" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Reports</a>
            </div>
        </div>
    </div>
</x-app-layout>
