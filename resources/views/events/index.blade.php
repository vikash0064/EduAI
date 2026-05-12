<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('School Events & Activities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Upcoming Events</h3>
                    @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                        <a href="#" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Add New Event</a>
                    @endif
                </div>
                
                <div class="space-y-4">
                    @forelse($activities ?? [] as $activity)
                        <div class="border p-4 rounded-md">
                            <h4 class="font-bold text-md">{{ $activity->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }} | {{ $activity->type }}</p>
                            <p class="text-gray-800">{{ $activity->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">No events found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
