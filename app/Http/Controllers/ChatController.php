<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function showChat($userId)
    {
        $user = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())
              ->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', auth()->id());
        })->orderBy('created_at')->get();

        return view('chat.index', compact('user', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return redirect()->back();
    }
}
