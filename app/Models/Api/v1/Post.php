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

    public function toggleLike(User $user)
    {
        $this->likes->contains($user->id) ?
            $this->likes()->detach($user->id)
        :
            $this->likes()->attach($user->id);
        
    }
}
