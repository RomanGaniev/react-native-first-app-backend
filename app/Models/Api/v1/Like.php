<?php

namespace App\Models\Api\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Api\v1\Post;

class Like extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

}
