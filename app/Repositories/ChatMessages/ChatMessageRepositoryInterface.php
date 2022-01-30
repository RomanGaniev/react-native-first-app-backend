<?php

namespace App\Repositories\ChatMessages;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ChatMessageRepositoryInterface
{
    public function getByChatId(int $chatId, int $userId): Collection;

    public function find(int $chatId, int $chatMessageId): Model;

    public function createFromArray(array $data): Model;
}
