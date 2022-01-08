<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\AddedNewPost;
use App\Events\PostLiked;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\PostCommentResource;
use App\Http\Resources\Api\User\PostResource;
use App\Models\Api\v1\Comment;
use Illuminate\Http\Request;
use App\Models\Api\v1\Post;
use App\Models\Api\v1\Like;
use Carbon\Carbon;

class PostController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function show(Request $request)
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

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
            broadcast(new PostLiked($post->id))->toOthers();

            return 'Добавлен лайк';
        } else {
            $like->delete();
            broadcast(new PostLiked($post->id))->toOthers();

            return 'Лайк убран';
        }
    }

    public function getComments(Request $request, Post $post) {
        $comments = Comment::wherePostId($post->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
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
