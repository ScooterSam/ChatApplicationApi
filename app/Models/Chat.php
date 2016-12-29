<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public function participants()
    {
        return $this->hasMany(Participant::class, 'chat_id', 'id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }
}
