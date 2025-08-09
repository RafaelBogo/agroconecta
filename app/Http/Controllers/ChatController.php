<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function index()
    {
        $myId = Auth::id();

        // lista distintos "outros usuários" com quem já troquei mensagens
        $threads = Message::selectRaw('IF(sender_id = ?, receiver_id, sender_id) as other_id', [$myId])
            ->where(function ($q) use ($myId) {
                $q->where('sender_id', $myId)->orWhere('receiver_id', $myId);
            })
            ->groupBy('other_id')
            ->get();

        $users = User::whereIn('id', $threads->pluck('other_id'))->orderBy('name')->get();

        return view('chat.inbox', compact('users'));
    }
}
