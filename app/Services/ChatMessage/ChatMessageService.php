<?php

namespace App\Services\ChatMessage;

use App\Events\ChatMessageSent;
use App\Events\ChatsUpdated;
use App\Http\Resources\Api\User\ChatMessageResource;
use App\Repositories\ChatMessages\ChatMessageRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatMessageService
{
    private $chatMessageRepository;

    public function __construct(ChatMessageRepositoryInterface $chatMessageRepository) {
        $this->chatMessageRepository = $chatMessageRepository;
    }

    /**
     * Get all chat messages by chat id.
     *
     * @param int $chatId
     * @return AnonymousResourceCollection
     */
    public function getAllMessagesByChatId(int $chatId): AnonymousResourceCollection
    {
        $userId = auth()->id();
        $messages = $this->chatMessageRepository->getByChatId($chatId, $userId);
        broadcast(new ChatsUpdated());

        return ChatMessageResource::collection($messages);
    }

    /**
     * Find chat message by chat id and chat message id.
     *
     * @param int $chatId
     * @param int $chatMessageId
     * @return ChatMessageResource
     */
    public function findChatMessage(int $chatId, int $chatMessageId): ChatMessageResource
    {
        $message = $this->chatMessageRepository->find($chatId, $chatMessageId);

        return new ChatMessageResource($message);
    }

    /**
     * Store chat message.
     *
     * @param int $chatId
     * @param array $data
     * @return ChatMessageResource
     */
    public function storeChatMessage(int $chatId, array $data): ChatMessageResource
    {
        $data['user_id'] = auth()->id();
        $data['chat_id'] = $chatId;

        $chatMessage = $this->chatMessageRepository->createFromArray($data);
        broadcast(new ChatMessageSent($chatId, $chatMessage->id))->toOthers();
        broadcast(new ChatsUpdated());

        return new ChatMessageResource($chatMessage);
    }
}
