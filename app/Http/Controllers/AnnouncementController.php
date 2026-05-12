<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('created_by')->latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'target_role' => 'required|string',
        ]);

        $announcement = Announcement::create(array_merge($validated, ['created_by' => Auth::id()]));

        // Notify Users based on target_role
        $query = User::query();
        if ($validated['target_role'] !== 'all') {
            $query->where('role', $validated['target_role']);
        }
        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\GeneralNotification(
                $announcement->title,
                $announcement->message,
                $announcement->type === 'emergency' ? 'alert' : 'info',
                $announcement->type === 'holiday' ? 'beach_access' : 'campaign'
            ));
        }

        return back()->with('success', 'Announcement published successfully!');
    }
}
