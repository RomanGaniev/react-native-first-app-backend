<?php

namespace App\Repositories\Friendship;

use App\Models\Post;

class EloquentFriendshipRepository implements FriendshipRepositoryInterface
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
