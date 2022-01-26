<?php

namespace App\Services\ChatMessage;

use App\Repositories\ChatMessages\ChatMessageRepositoryInterface;

class ChatMessageService
{
    private $chatMessageRepository;

    public function __construct(ChatMessageRepositoryInterface $chatMessageRepository) {
        $this->chatMessageRepository = $chatMessageRepository;
    }
}
