<?php

namespace App\Repositories\Posts;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface PostRepositoryInterface
{
    public function getAll(): Collection;

    public function find(int $id): Model;

    public function like(Model $post, int $userId): void;

    public function createFromArray(array $data): Model;
}
