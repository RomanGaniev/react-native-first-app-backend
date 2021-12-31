<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\PostChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\PostCommentResource;
use App\Http\Resources\Api\User\PostResource;
use App\Models\Api\v1\Comment;
use Illuminate\Http\Request;
use App\Models\Api\v1\Post;
use App\Models\Api\v1\Like;

class PostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $posts = Post::all();

        return PostResource::collection($posts);
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
        Post::create([
            'user_id' => $user->id,
            'data' => [
                'text' => $text,
                'image' => $imagePath ?? null
            ]
        ]);
    }

    public function showOne(Request $request, Post $post)
    {
        $post->views = $post->views + 1;
        $post->save();

        return new PostResource($post);
    }

    public function like(Request $request, Post $post)
    {
        $user = auth()->user();

        $like = Like::wherePostId($post->id)->whereUserId($user->id)->first();
        if (!$like) {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);
            broadcast(new PostChanged($post->id))->toOthers();

            return 'Добавлен лайк';
        } else {
            $like->delete();
            broadcast(new PostChanged($post->id))->toOthers();

            return 'Лайк убран';
        }
    }

    public function sendImage(Request $request) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filePath = $file->storeAs(
                'test_images',
                date('YmdHis') . '.' . $file->extension(),
                'public'
            );

            return $filePath;

        } else {
            return 'нет файла';
        }
    }

    public function getImage(Request $request) {
        $file = asset( 'storage/' . 'test_images/20211106174223.png' );
        
        return $file;
    }

    public function getComments(Request $request, Post $post) {
        $comments = Comment::wherePostId($post->id)->get();
        
        return PostCommentResource::collection($comments);
    }

    public function addComment(Request $request)
    {
        $post_id = $request->get('postId');
        $title = $request->get('comment');

        $user = auth()->user();

        Comment::create([
            'title' => $title,
            'post_id' => $post_id,
            'user_id' => $user->id,
        ]);
    }
}
