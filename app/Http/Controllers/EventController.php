<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('event_date', 'asc')->get();
        return view('events.index', compact('activities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);

        Activity::create($validated);
        return back()->with('success', 'Event created successfully.');
    }

    public function register(Request $request, Activity $event)
    {
        ActivityLog::updateOrCreate([
            'activity_id' => $event->id,
            'user_id' => Auth::id(),
        ], [
            'status' => 'registered'
        ]);

        return back()->with('success', 'Registered for event successfully.');
    }
}
