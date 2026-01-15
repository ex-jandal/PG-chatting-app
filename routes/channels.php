<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return DB::table('conversations_user')
        ->where('conversation_id', $conversationId)
        /* ->where('user_id', $user->id) */
        ->exists();
});
