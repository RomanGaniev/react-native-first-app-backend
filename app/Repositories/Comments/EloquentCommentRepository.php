<?php

namespace App\Repositories\Comments;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    /**
     * @param int $id
     * @return Builder[]|Collection
     */
    public function getByPostId(int $id): Collection
    {
        return Comment::query()->where('post_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param array $data
     * @return Builder|Model
     */
    public function createFromArray(array $data): Model
    {
        return Comment::query()->create($data);
    }
}
