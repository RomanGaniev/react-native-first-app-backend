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
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function getByPostId(int $id): AnonymousResourceCollection
    {
        return $this->commentRepository->getByPostId($id);
    }

    /**
     * @param int $post_id
     * @param array $data
     * @return CommentResource
     */
    public function storeCommentByPostId(int $post_id, array $data): CommentResource
    {
        $user_id = auth()->user()->id;
        $data = [
            'user_id' => $user_id,
            'title' => $data['title'],
            'post_id' => $post_id,
        ];

        return $this->commentRepository->createFromArray($data);
    }

}
