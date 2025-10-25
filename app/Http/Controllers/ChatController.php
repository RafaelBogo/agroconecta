<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $myId = Auth::id();

        $otherIds = Message::selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as other_id', [$myId])
            ->where(fn($q) => $q->where('sender_id', $myId)->orWhere('receiver_id', $myId))
            ->groupBy('other_id')
            ->pluck('other_id')
            ->filter(fn($id) => (int)$id !== (int)$myId);

        $users = User::whereIn('id', $otherIds)->orderBy('name')->get();

        return view('chat.inbox', compact('users'));
    }

    public function showChat($userId)
    {
        abort_if((int)$userId === (int)Auth::id(), 404);

        $user = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($userId) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at')
            ->get();

        return view('chat.index', compact('user', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|exists:users,id|different:' . Auth::id(),
            'message'     => 'required|string|min:1|max:2000',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $data['receiver_id'],
            'message'     => trim($data['message']),
        ]);

        return back();
    }

    public function endConversation($userId)
    {
        Message::where(function ($q) use ($userId) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)->where('receiver_id', Auth::id());
            })
            ->delete();

        return redirect()->route('messages')->with('success', 'Conversa encerrada com sucesso.');
    }
}
