<?php

namespace App\Http\Controllers;

use App\Events\MessageRead;
use App\Events\MessageSent;
use App\Events\UserStopTypingEvent;
use App\Events\UserTypingEvent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $auth = Auth::user();

        $users = User::query()
            ->when($auth->role === 'customer', fn($q) => $q->where('role', 'admin'))
            ->when($auth->role === 'admin', fn($q) => $q->where('id', '!=', $auth->id))
            ->orderBy('nama')
            ->get();

        $admin = null;
        if ($auth->role === 'customer') {
            $admin = $users->first();
        }
        $view = $auth->role === 'admin' ? 'admin.chat.index' : 'users.chat.index';

        return view($view, compact('users', 'auth', 'admin'));
    }


    public function fetch(User $user)
    {
        $authId = Auth::id();
        $messages = Chat::between($authId, $user->id)
            ->orderBy('created_at')
            ->take(200)
            ->get();

        Chat::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        broadcast(new MessageRead($authId, $user->id))->toOthers();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240' // 10 MB
        ]);

        $chat = new Chat();
        $chat->sender_id = auth()->id();
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message ?? '';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public');
            $chat->file_path = $path;
            $chat->file_name = $file->getClientOriginalName();
            $chat->file_type = $file->getMimeType();
        }

        $chat->save();

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json($chat);
    }


    public function pingOnline()
    {
        $u = Auth::user();
        $u->forceFill(['is_online' => true, 'last_seen_at' => now()])->save();
        return response()->json(['ok' => true]);
    }


    public function typing(Request $request)
    {
        broadcast(new UserTypingEvent(Auth::id(), $request->receiver_id))->toOthers();
        return response()->json(['status' => 'ok']);
    }

    public function stopTyping(Request $request)
    {
        broadcast(new UserStopTypingEvent(Auth::id(), $request->receiver_id))->toOthers();
        return response()->json(['status' => 'ok']);
    }

    public function markAsRead($userId)
    {
        $user = Auth::user();

        $updated = Chat::where('sender_id', $userId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($updated > 0) {
            broadcast(new MessageRead($userId, $user->id))->toOthers();
        }

        return response()->json(['status' => 'ok']);
    }
}
