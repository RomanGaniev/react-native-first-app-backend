<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
		return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
	}

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function toggleLike(int $userId)
    {
        $this->likes->contains($userId) ?
            $this->likes()->detach($userId)
        :
            $this->likes()->attach($userId);

    }
}
