<?php

namespace App\Repositories\Comments;

use App\Http\Resources\Api\User\CommentResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface CommentRepositoryInterface
{
    public function getByPostId(int $id): AnonymousResourceCollection;

    public function createFromArray(array $data): CommentResource;
}
