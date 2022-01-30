<?php

namespace App\Repositories\ChatMessages;

use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentChatMessageRepository implements ChatMessageRepositoryInterface
{
    /**
     * @param int $chatId
     * @param int $userId
     * @return Collection
     */
    public function getByChatId(int $chatId, int $userId): Collection
    {
        $chat = Chat::query()->find($chatId);
        $chat->readAllMessagesForUser($userId);

        return $chat->messages;
    }

    /**
     * @param int $chatId
     * @param $chatMessageId
     * @return Model
     */
    public function find(int $chatId, $chatMessageId): Model
    {
        return ChatMessage::query()->where('chat_id', $chatId)
            ->find($chatMessageId);
    }

    /**
     * @param array $data
     * @return Model
     */
    public function createFromArray(array $data): Model
    {
        return ChatMessage::query()->create($data);
    }
}
