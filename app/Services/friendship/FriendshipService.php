<?php

namespace App\Services\Chat;

use App\Services\Chat\Repositories\ChatRepositoryInterface;

class ChatService
{
    private $chatRepository; 

    public function __construct(ChatRepositoryInterface $chatRepository) {
        $this->chatRepository = $chatRepository;
    }
}