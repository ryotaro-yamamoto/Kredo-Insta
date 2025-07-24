<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @property string $content
 */

class MessageController extends Controller
{
    // app/Http/Controllers/MessageController.php

    public function index()
    {
        $auth = Auth::user();

        // やりとり済みユーザー
        $messagedUserIds = Message::where('sender_id', $auth->id)
            ->orWhere('receiver_id', $auth->id)
            ->get()
            ->flatMap(function ($msg) use ($auth) {
                return $msg->sender_id == $auth->id ? [$msg->receiver_id] : [$msg->sender_id];
            })
            ->unique()
            ->values();

        $messagedUsers = User::whereIn('id', $messagedUserIds)->get();

        // フォロー中のユーザー（followings() が定義されている前提）
        $followingUsers = $auth->messageFollowing()->get();  // Eloquentリレーション：$user->followings()

        // まだチャットしていないフォロー中ユーザー
        $newChatUsers = $followingUsers->whereNotIn('id', $messagedUserIds);

        return view('users.messages.index', [
            'users' => $messagedUsers,
            'newChatUsers' => $newChatUsers,
        ]);
    }

    public function chat($userId)
    {
        $authId = Auth::id();

        // chat() メソッド内
        $messages = Message::where(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $authId)->where('receiver_id', $userId);
        })
        ->orWhere(function ($q) use ($authId, $userId) {
            $q->where('sender_id', $userId)->where('receiver_id', $authId);
        })
        ->orderByDesc('created_at') // ←ここを変更
        ->get();


        $partner = User::findOrFail($userId);

        return view('users.messages.chat', compact('messages', 'partner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'content' => $request->content
        ]);

        return redirect()->back();
    }
}