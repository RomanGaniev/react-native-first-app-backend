<?php

namespace App\Models\Api\v1\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Api\v1\Chat\ChatMessage;

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
}
