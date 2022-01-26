<?php

namespace App\Services\Post\Repositories;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function find(int $id);

    public function search(array $filters = []);

    public function createFromArray(array $data): Post;

    public function updateFromArray(Post $post, array $data);
}