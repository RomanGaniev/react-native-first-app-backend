<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Api\v1\Like;
use App\Models\Api\v1\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
    ];

    protected $casts = [
        'data'          => 'array',
    ];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes() 
    {
        return $this->hasMany(Like::class);
    }

    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }
}
