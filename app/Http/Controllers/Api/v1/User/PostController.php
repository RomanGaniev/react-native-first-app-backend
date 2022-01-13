<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\AddedNewPost;
use App\Events\PostLiked;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\PostResource;
use Illuminate\Http\Request;
use App\Models\Api\v1\Post;

class PostController extends Controller
{
    public function getPosts(Request $request)
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return PostResource::collection($posts);
    }

    public function detailt(Request $request, Post $post)
    {
        $post->views = $post->views + 1;
        $post->save();

        return new PostResource($post);
    }

    public function create(Request $request)
    {
        $text       = $request->get('text');
        $image      = $request->file('image');

        $user = auth()->user();

        if ($image) {
            $imagePath = $image->storeAs(
                'posts_images',
                date('YmdHis') . '.' . $image->extension(),
                'public'
            );

        }
        $post = Post::create([
            'user_id' => $user->id,
            'data' => [
                'text' => $text,
                'image' => $imagePath ?? null
            ]
        ]);

        broadcast(new AddedNewPost($post->id));

        return new PostResource($post);
    }


    public function toggleLike(Request $request, Post $post)
    {
        $user = auth()->user();

        $post->toggleLike($user);
        
        broadcast(new PostLiked($post->id))->toOthers();
    }
}
