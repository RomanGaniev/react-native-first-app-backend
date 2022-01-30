<?php

namespace App\Repositories\Posts;

use App\Http\Resources\Api\User\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentPostRepository implements PostRepositoryInterface
{
    /**
     * @return Builder[]|Collection
     */
    public function getAll(): Collection
    {
        return Post::query()->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return Post::query()->find($id);
    }

    /**
     * @param Post $post
     * @param int $userId
     * @return void
     */
    public function like(Model $post, int $userId): void
    {
        $post->toggleLike($userId);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function createFromArray(array $data): Model
    {
        return Post::query()->create($data);
    }
}
