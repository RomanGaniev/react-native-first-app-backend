<?php

namespace App\Services\Chat\Repositories;

use App\Models\Chat;

class EloquentChatRepository implements ChatRepositoryInterface
{
    public function find(int $id)
    {
        return Chat::find($id);
    }

    public function search(array $filters = [])
    {
        
    }

    public function createFromArray(array $data): Chat
    {
        
    }

    public function updateFromArray(Chat $chat, array $data)
    {
        
    }
}