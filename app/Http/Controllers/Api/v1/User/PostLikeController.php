<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\PostLiked;
use App\Http\Controllers\Controller;
use App\Models\Api\v1\Like;
use App\Models\Api\v1\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggleLike(Request $request, Post $post)
    {
        $user = auth()->user();

        $like = Like::wherePostId($post->id)->whereUserId($user->id)->first();
        if (!$like) {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            broadcast(new PostLiked($post->id))->toOthers();

            return 'Добавлен лайк';
        } else {
            $like->delete();
            broadcast(new PostLiked($post->id))->toOthers();

            return 'Лайк убран';
        }
    }
}
