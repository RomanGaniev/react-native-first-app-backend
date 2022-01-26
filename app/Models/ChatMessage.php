<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use App\Models\User;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'chat_id',
        'user_id',
        'read',
        'system'
    ];

    protected $casts = [
        'read'  => 'boolean',
        'system'  => 'boolean',
    ];

    public function chat() 
    {
        return $this->belongsTo(Chat::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function read() 
    {
        $this->update(['read' => true]);
    }
}
