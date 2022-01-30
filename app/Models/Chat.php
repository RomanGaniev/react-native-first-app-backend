<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
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

    public function readAllMessagesForUser(int $userId)
    {
        $this->messages()
            ->where('user_id', '<>', $userId)
            ->whereRead(false)
            ->update(['read' => true]);
    }
}
