<?php

namespace App\Services\Chat;

use App\Events\ChatsUpdated;
use App\Http\Resources\Api\User\ChatResource;
use App\Repositories\ChatMessages\ChatMessageRepositoryInterface;
use App\Repositories\Chats\ChatRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ChatService
{
    private $chatRepository;
    private $chatMessageRepository;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        ChatMessageRepositoryInterface $chatMessageRepository

    ) {
       $this->chatRepository = $chatRepository;
       $this->chatMessageRepository = $chatMessageRepository;
    }

    /**
     * Get all chats of an authenticated user.
     *
     * @return AnonymousResourceCollection
     */
    public function getAll(): AnonymousResourceCollection
    {
        $user = auth()->user();

        return $this->chatRepository->getByUser($user);
    }

    /**
     * Find chat by id.
     *
     * @param int $id
     * @return ChatResource
     */
    public function findChat(int $id): ChatResource
    {
        return $this->chatRepository->find($id);
    }

    /**
     * Store chat depending on the type.
     *
     * @param array $data
     * @return ChatResource
     */
    public function storeChat(array $data): ChatResource
    {
        $userId = auth()->id();

        if($data['type'] === 'group') {
            // TODO: изолировать сохранение файлов в отдельный класс.
            if (isset($data['avatar'])) {
                $avatarPath = $data['avatar']->storeAs(
                    'chat_avatars',
                    $data['name'] . '_' . date('YmdHis') . '.' . $data['avatar']->extension(),
                    'public'
                );
            }
            $participantsIds = $data['participants'];
            $participantsIds[] = $userId;
            $array = [
                'is_private' => false,
                'name' => $data['name'],
                'avatar' => $avatarPath
            ];
            $systemMessage = 'created_group_chat';
        } elseif ($data['type'] === 'private') {
            // TODO: добавить проверку существует ли приватный чат с interlocutor
            $participantsIds = $data['interlocutor_id'];
            $participantsIds[] = $userId;
            $array = ['is_private' => true];
            $systemMessage = 'created_private_chat';
        }

        $chat = $this->chatRepository->createChatFromArray($array, $participantsIds);
        $this->chatMessageRepository->createFromArray([
            'user_id' => $userId,
            'chat_id' => $chat->id,
            'text' => $systemMessage,
            'system' => true
        ]);
        broadcast(new ChatsUpdated());

        return new ChatResource($chat);
    }

    /**
     * Update group chat.
     *
     * @param int $id
     * @param array $data
     * @return ChatResource
     */
    public function updateChat(int $id, array $data): ChatResource
    {
        // TODO: изолировать сохранение файлов в отдельный класс.
        if(isset($data['avatar'])) {
            $randomAvatarName = Str::random(10);
            $avatarPath = $data['avatar']->storeAs(
                'chat_avatars',
                $randomAvatarName . '_' . $data['name'] . '.' . $data['avatar']->extension(),
                'public'
            );
        }
        $participantsIds = $data['participants'];
        $participantsIds[] = auth()->id();
        $updatedChat = $this->chatRepository->updateFromArray($id, $participantsIds, $data);
        broadcast(new ChatsUpdated());

        return new ChatResource($updatedChat);
    }

    /**
     * Delete chat by id.
     *
     * @param int $id
     * @return void
     */
    public function destroyChat(int $id)
    {
        $this->chatRepository->destroy($id);
        broadcast(new ChatsUpdated());
    }

    /**
     * Get participants ids of chat by chat id.
     *
     * @param int $id
     * @return mixed
     */
    public function getParticipantsIdsByChatId(int $id)
    {
        $userId = auth()->id();

        return $this->chatRepository->getParticipantsIdsByChatId($id, $userId);
    }
}
