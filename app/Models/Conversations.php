<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    public function messages() {
        return $this->hasMany(Message::class, 'conversations_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'conversations_user');
    }
}
