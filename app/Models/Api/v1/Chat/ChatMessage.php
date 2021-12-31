<?php

namespace App\Models\Api\v1\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\v1\Chat\Chat;
use App\Models\User;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'chat_id',
        'user_id'
    ];

    public function chat() 
    {
        return $this->belongsTo(Chat::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
