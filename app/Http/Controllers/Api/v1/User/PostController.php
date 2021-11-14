<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\PostChanged;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\PostResource;
use Illuminate\Http\Request;
use App\Models\Api\v1\Post;
use App\Models\Api\v1\Like;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

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
        $file       = $request->file('image');

        $user = auth()->user();

        if ($file) {
            $filePath = $file->storeAs(
                'posts_images',
                date('YmdHis') . '.' . $file->extension(),
                'public'
            );

        }
        $post = new Post;
        $post->user_id = $user->id;
        $post->data = [
            'text' => $text,
            'image' => $filePath
        ];
        $post->save();

    }

    public function showOne(Request $request, Post $post)
    {
        return new PostResource($post);
    }

    public function like(Request $request, Post $post)
    {
        $user = auth()->user();

        $like = Like::wherePostId($post->id)->whereUserId($user->id)->first();
        if (!$like) {
            $like = new Like;
            $like->post_id = $post->id;
            $like->user_id = $user->id;
            $like->save();

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

        // $file = $storagePath.'test_images/20211106171743.png';
        //  Storage::get('test_images/20211106171743.png');
        
        return $file;
    }

}
