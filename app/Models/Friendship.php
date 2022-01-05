<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_user',
        'second_user',
        'acted_user',
        'status',
    ];

    public function firstUser()
    {
        return $this->belongsTo(User::class, 'first_user');
    }

    public function secondUser()
    {
        return $this->belongsTo(User::class, 'second_user');
    }

    public function actedUser()
    {
        return $this->belongsTo(User::class, 'acted_user');
    }
}
