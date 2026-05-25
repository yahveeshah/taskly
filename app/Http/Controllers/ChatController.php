<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $type = $request->query('type', 'group');
        $withUserId = $request->query('with');

        $messages = collect();
        if ($type === 'group' && $user->team_id) {
            $messages = Message::groupChat($user->team_id)
                ->with('sender:id,name')
                ->orderBy('created_at', 'asc')
                ->take(50)
                ->get();
        } elseif ($type === 'dm' && $withUserId) {
            $messages = Message::dm($user->id, $withUserId)
                ->with('sender:id,name')
                ->orderBy('created_at', 'asc')
                ->take(50)
                ->get();
        }

        return view('chat.index', compact('messages', 'type', 'withUserId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'type' => 'required|in:group,dm',
            'receiver_id' => 'required_if:type,dm|exists:users,id'
        ]);

        $user = auth()->user();

        if ($validated['type'] === 'dm') {
            $receiver = User::findOrFail($validated['receiver_id']);
            if (!$user->team_id || $user->team_id !== $receiver->team_id) {
                abort(403, 'Unauthorized DM');
            }
        } elseif ($validated['type'] === 'group') {
            if (!$user->team_id) {
                abort(403, 'User not in a team');
            }
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $validated['receiver_id'] ?? null,
            'team_id' => $user->team_id,
            'body' => $validated['body'],
            'type' => $validated['type']
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message->load('sender:id,name'));
    }

    public function getTeamMembers()
    {
        $user = auth()->user();
        if (!$user->team_id) {
            return response()->json([]);
        }

        $members = User::where('team_id', $user->team_id)
            ->where('id', '!=', $user->id)
            ->select('id', 'name')
            ->get();

        return response()->json($members);
    }
}
