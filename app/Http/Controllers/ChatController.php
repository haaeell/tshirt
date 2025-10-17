<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Events\MessageSent;
use App\Events\UserTyping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($receiverId)
    {
        $messages = Chat::where(function ($q) use ($receiverId) {
            $q->where('sender_id', Auth::id())
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at')
            ->get();
        dd([
            'auth_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'messages_count' => $messages->count(),
            'messages' => $messages->toArray(),
        ]);

        return view('users.chat.index', compact('messages', 'receiverId'));
    }

    public function getHistory($receiverId)
    {
        $messages = Chat::where(function ($q) use ($receiverId) {
            $q->where('sender_id', Auth::id())
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($q) use ($receiverId) {
                $q->where('sender_id', $receiverId)
                    ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at')
            ->get();

        return response()->json(['messages' => $messages]);
    }


    public function admin()
    {
        $users = \App\Models\User::where('role', 'customer')->get();
        return view('admin.chat.index', compact('users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($chat))->toOthers();

        return response()->json($chat);
    }



    public function typing(Request $request)
    {
        broadcast(new UserTyping(Auth::id(), $request->receiver_id))->toOthers();
        return response()->json(['status' => 'ok']);
    }
}
