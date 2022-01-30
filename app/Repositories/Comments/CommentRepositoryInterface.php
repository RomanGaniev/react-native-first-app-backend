<?php

namespace App\Repositories\Comments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function getByPostId(int $id): Collection;

    public function createFromArray(array $data): Model;
}
