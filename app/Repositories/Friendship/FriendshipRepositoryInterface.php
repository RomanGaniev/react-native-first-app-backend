<?php

namespace App\Repositories\Friendship;

use App\Models\Post;

interface FriendshipRepositoryInterface
{
    public function find(int $id);

    public function search(array $filters = []);

    public function createFromArray(array $data): Post;

    public function updateFromArray(Post $post, array $data);
}
