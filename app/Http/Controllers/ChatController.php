<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $conversations = $user->conversations()->with(['latestMessage', 'users'])->get();
        
        $activeConversation = null;
        if ($request->has('conversation_id')) {
            $activeConversation = Conversation::with(['messages.user', 'users'])
                ->findOrFail($request->conversation_id);
        } elseif ($conversations->count() > 0) {
            $activeConversation = $conversations->first()->load(['messages.user', 'users']);
        }

        // Fetch contacts for starting new chats (filtered based on roles)
        $contacts = User::where('id', '!=', $user->id);
        if ($user->role === 'student') {
            $contacts->whereIn('role', ['teacher', 'admin']);
        } elseif ($user->role === 'teacher') {
            $contacts->whereIn('role', ['student', 'admin', 'teacher']);
        }
        $contacts = $contacts->get();

        return view('messages.index', compact('conversations', 'activeConversation', 'contacts'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('chat_attachments', 'public');
        }

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'user_id' => Auth::id(),
            'body' => $validated['body'] ?? '',
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Message sent!');
    }

    public function startConversation(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUser = User::find($validated['user_id']);

        // Check if a direct conversation already exists between these two users
        $existing = Auth::user()->conversations()
            ->where('type', 'direct')
            ->whereHas('users', function($q) use ($validated) {
                $q->where('users.id', $validated['user_id']);
            })->first();

        if ($existing) {
            return redirect()->route('messages.index', ['conversation_id' => $existing->id]);
        }

        $conversation = Conversation::create([
            'type' => 'direct',
            'created_by' => Auth::id(),
        ]);

        $userIds = [Auth::id(), $validated['user_id']];
        
        // Auto-add parents if the target is a student
        if ($targetUser->isStudent() && $targetUser->studentProfile) {
            $parentIds = $targetUser->studentProfile->parents()->pluck('user_id')->toArray();
            $userIds = array_merge($userIds, $parentIds);
        }

        $conversation->users()->attach(array_unique($userIds));

        return redirect()->route('messages.index', ['conversation_id' => $conversation->id]);
    }

    public function createGroup(Request $request)
    {
        if (Auth::user()->role === 'student') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $conversation = Conversation::create([
            'name' => $validated['name'],
            'type' => 'group',
            'created_by' => Auth::id(),
        ]);

        $userIds = array_merge($validated['user_ids'], [Auth::id()]);
        
        // Auto-add parents for all students in the group
        $students = User::whereIn('id', $validated['user_ids'])->where('role', 'student')->with('studentProfile.parents')->get();
        foreach ($students as $student) {
            if ($student->studentProfile) {
                $parentIds = $student->studentProfile->parents()->pluck('user_id')->toArray();
                $userIds = array_merge($userIds, $parentIds);
            }
        }

        $conversation->users()->attach(array_unique($userIds));

        return redirect()->route('messages.index', ['conversation_id' => $conversation->id]);
    }

    public function addMember(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::findOrFail($validated['conversation_id']);
        $user = User::findOrFail($validated['user_id']);
        
        if (!$conversation->users->contains($user->id)) {
            $conversation->users()->attach($user->id);
            
            // Auto-add parents if the new member is a student
            if ($user->isStudent() && $user->studentProfile) {
                $parentIds = $user->studentProfile->parents()->pluck('user_id')->toArray();
                foreach ($parentIds as $pId) {
                    if (!$conversation->users->contains($pId)) {
                        $conversation->users()->attach($pId);
                    }
                }
            }
        }

        return back()->with('success', 'Member added successfully!');
    }
}
