<?php

namespace App\Repositories\Comments;

use App\Http\Resources\Api\User\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EloquentCommentRepository implements CommentRepositoryInterface
{
    /**
     * Get all posts.
     *
     * return
     */
    public function getByPostId(int $id): AnonymousResourceCollection
    {
        $postComments = Comment::query()->wherePostId($id)
            ->orderBy('created_at', 'desc')
            ->get();

        return CommentResource::collection($postComments);
    }

    /**
     * @param array $data
     * @return CommentResource
     */
    public function createFromArray(array $data): CommentResource
    {
        $comment = Comment::query()->create($data);

        return new CommentResource($comment);
    }
}
