<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ChatMessage;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_private'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages() 
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class)->where('read', false);
    }

    public function detachAllUsers()
    {
        $this->users()->detach();
    }

    public function readAllMessagesForUser(User $user)
    {
        $this->messages()
            ->where('user_id', '<>', $user->id)
            ->whereRead(false)
            ->update(['read' => true]);
    }
}
