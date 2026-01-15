<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index($id = null)
    {
        $authId = auth()->guard()->id();

        $conversations = DB::table('conversations')
            ->join('conversations_user as me', 'conversations.id', '=', 'me.conversation_id')
            ->join('conversations_user as them', 'conversations.id', '=', 'them.conversation_id')
            ->join('users', 'them.user_id', '=', 'users.id')
            ->where('me.user_id', $authId)
            ->where('them.user_id', '!=', $authId)
            ->select('conversations.id', 'users.name as chat_name', 'conversations.updated_at')
            ->orderBy('conversations.updated_at', 'desc')
            ->get();

        $messages = [];
        if ($id) {
            $messages = DB::table('messages')
                ->join('users', 'messages.user_id', '=', 'users.id')
                ->where('conversations_id', $id)
                ->select('messages.id', 'messages.user_id', 'messages.content', 'messages.created_at', 'users.name as user_name')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        $allUsers = DB::table('users')->where('id', '!=', $authId)->get();

        return Inertia::render('user/Chats', [
            'conversations' => $conversations,
            'messages' => $messages,
            'allUsers' => $allUsers,
            'activeId' => $id ? (int)$id : null,
            'authId' => $authId
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $authId = auth()->guard()->id();
        $targetId = $request->user_id;

        $existing = DB::table('conversations_user as cu1')
            ->join('conversations_user as cu2', 'cu1.conversation_id', '=', 'cu2.conversation_id')
            ->where('cu1.user_id', $authId)
            ->where('cu2.user_id', $targetId)
            ->select('cu1.conversation_id')
            ->first();

        if ($existing) {
            return redirect()->route('chat.index', $existing->conversation_id);
        }

        return DB::transaction(function () use ($authId, $targetId) {
            // 1. Create the conversation
            $convId = DB::table('conversations')->insertGetId([
                'is_group' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('conversations_user')->insert([
                ['conversation_id' => $convId, 'user_id' => $authId],
                ['conversation_id' => $convId, 'user_id' => $targetId],
            ]);

            return redirect()->route('chat.index', ['id' => $convId]);
        });
    }

    public function store(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        $messageId = DB::table('messages')->insertGetId([
            'conversations_id' => (int)$id,
            'user_id' => auth()->guard()->id(),
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $message = DB::table('messages')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->where('messages.id', $messageId)
            ->select('messages.*', 'users.name as user_name')
            ->first();

        broadcast(new MessageSent($message));

        return back();
    }
}
