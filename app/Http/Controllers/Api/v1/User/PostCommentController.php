<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\PostCommentResource;
use App\Models\Api\v1\Comment;
use App\Models\Api\v1\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function getComments(Request $request, Post $post) {
        $comments = Comment::wherePostId($post->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return PostCommentResource::collection($comments);
    }

    public function create(Request $request, Post $post)
    {
        $title = $request->get('comment');

        $user = auth()->user();

        Comment::create([
            'title'     => $title,
            'post_id'   => $post->id,
            'user_id'   => $user->id,
        ]);
    }
}
