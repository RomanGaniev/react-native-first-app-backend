<?php

namespace App\Repositories\ChatMessages;

use App\Models\ChatMessage;

class EloquentChatMessageRepository implements ChatMessageRepositoryInterface
{
    public function find(int $id)
    {
        return ChatMessage::find($id);
    }

    public function search(array $filters = [])
    {

    }

    public function createFromArray(array $data): ChatMessage
    {

    }

    public function updateFromArray(ChatMessage $chatMessage, array $data)
    {

    }
}
