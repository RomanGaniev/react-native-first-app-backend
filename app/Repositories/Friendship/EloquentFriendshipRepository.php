<?php

namespace App\Services\Post\Repositories;

use App\Models\Post;
use App\Repositories\Posts\PostCommentsRepositoryInterface;

class EloquentPostCommentsRepository implements PostCommentsRepositoryInterface
{
    public function find(int $id)
    {
        return Post::find($id);
    }

    public function search(array $filters = [])
    {

    }

    public function createFromArray(array $data): Post
    {

    }

    public function updateFromArray(Post $post, array $data)
    {

    }
}
