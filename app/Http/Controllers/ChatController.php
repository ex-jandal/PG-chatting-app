<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

use function Illuminate\Log\log;

class ChatController extends Controller
{
    public function index($id = null)
    {
        $authId = auth()->guard()->id();

        $conversations = DB::table('conversations_user as them')
            ->select('them.conversation_id as id', 'u.name as chat_name')
            ->join('users as u', 'them.user_id', '=', 'u.id')
            ->join('conversations_user as me', 'them.conversation_id', '=', 'me.conversation_id')
            ->where('me.user_id', $authId)
            ->where('them.user_id', '!=', $authId)
            ->orderBy('created_at', 'asc')
            ->get();

        $messages = [];
        if ($id) {
            $messages = DB::table('messages')
                ->select('messages.id', 'messages.user_id', 'messages.content', 'messages.created_at', 'users.name as user_name')
                ->join('users', 'messages.user_id', '=', 'users.id')
                ->where('conversations_id', $id)
                ->orderBy('created_at', 'asc')
                ->get();
        }
        // log(dump($messages));

        $allUsers = DB::table('users')
            ->select('id', 'name')
            ->where('id', '!=', $authId)->get();
        /* log(dump($allUsers)); */

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
            ->select('cu1.conversation_id')
            ->join('conversations_user as cu2', 'cu1.conversation_id', '=', 'cu2.conversation_id')
            ->where('cu1.user_id', $authId)
            ->where('cu2.user_id', $targetId)
            ->first();

        if ($existing) {
            return redirect()->route('chat.index', $existing->conversation_id);
        }

        return DB::transaction(function () use ($authId, $targetId) {
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
            ->select('messages.*', 'users.name as user_name')
            ->join('users', 'messages.user_id', '=', 'users.id')
            ->where('messages.id', $messageId)
            ->first();

        broadcast(new MessageSent($message));

        return back();
    }
}
