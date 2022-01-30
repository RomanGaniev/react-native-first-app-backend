<?php

namespace App\Services\Comment;

use App\Http\Resources\Api\User\CommentResource;
use App\Repositories\Comments\CommentRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentService
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository) {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get comments by post id.
     *
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function getByPostId(int $id): AnonymousResourceCollection
    {
        $comments = $this->commentRepository->getByPostId($id);

        return CommentResource::collection($comments);
    }

    /**
     * Store comment.
     *
     * @param int $id
     * @param array $data
     * @return CommentResource
     */
    public function storeCommentByPostId(int $id, array $data): CommentResource
    {
        $userId = auth()->id();
        $data['post_id'] = $id;
        $data['user_id'] = $userId;

        $comment = $this->commentRepository->createFromArray($data);

        return new CommentResource($comment);
    }
}
