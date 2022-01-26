<?php

namespace App\Repositories\Posts;

use App\Http\Resources\Api\User\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EloquentPostRepository implements PostRepositoryInterface
{
    /**
     * Get all posts.
     *
     * return
     */
    public function getAll(): AnonymousResourceCollection
    {
        $posts = Post::query()->orderBy('created_at', 'desc')->get();
        return PostResource::collection($posts);
    }

    /**
     * Get post by id.
     *
     * @param int $id
     * @return PostResource
     */
    public function find(int $id): PostResource
    {
        $post = Post::query()->find($id);

        return new PostResource($post);
    }

    /**
     * @param Post $post
     * @param User $user
     * @return Post
     */
    public function like(Post $post, User $user): Post
    {
        $post->toggleLike($user);

        return $post;
    }

    /**
     * @param array $data
     * @return PostResource
     */
    public function createFromArray(array $data): PostResource
    {
        $post = Post::query()->create($data);

        return new PostResource($post);
    }

    public function updateFromArray(Post $post, array $data)
    {

    }
}
