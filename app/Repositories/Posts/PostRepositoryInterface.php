<?php

namespace App\Repositories\Posts;

use App\Http\Resources\Api\User\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface PostRepositoryInterface
{
    public function getAll(): AnonymousResourceCollection;

    public function find(int $id): PostResource;

    public function like(Post $post, User $user): Post;

    public function createFromArray(array $data): PostResource;

    public function updateFromArray(Post $post, array $data);
}
