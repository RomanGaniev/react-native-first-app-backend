<?php

namespace App\Repositories\ChatMessages;

use App\Models\ChatMessage;

interface ChatMessageRepositoryInterface
{
    public function find(int $id);

    public function search(array $filters = []);

    public function createFromArray(array $data): ChatMessage;

    public function updateFromArray(ChatMessage $chatMessage, array $data);
}
