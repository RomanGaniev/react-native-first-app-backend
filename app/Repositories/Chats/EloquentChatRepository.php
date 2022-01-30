<?php

namespace App\Repositories\Chats;

use App\Http\Resources\Api\User\ChatResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EloquentChatRepository implements ChatRepositoryInterface
{
    /**
     * Get all chats of a user.
     *
     * @param Authenticatable $user
     * @return AnonymousResourceCollection
     */
    public function getByUser(Authenticatable $user): AnonymousResourceCollection
    {
        $chats = $user->chats
            ->sortByDesc('latestMessage.created_at');

        return ChatResource::collection($chats);
    }

    /**
     * @param array $data
     * @param array $participantsIds
     * @return ChatResource
     */
    public function createChatFromArray(array $data, array $participantsIds): ChatResource
    {
        $chat = Chat::query()->create($data);
        $chat->users()->attach([...$participantsIds]);

        return new ChatResource($chat);
    }

    /**
     * Find chat by id.
     *
     * @param int $id
     * @return ChatResource
     */
    public function find(int $id): ChatResource
    {
        $chat = Chat::query()->find($id);

        return new ChatResource($chat);
    }

    /**
     * @param int $id
     * @param array $data
     * @param array $participantsIds
     * @return Model
     */
    public function updateFromArray(int $id, array $data, array $participantsIds)
    {
        $chat = Chat::query()->find($id);
        $chat->update($data);

        $chat->detachAllUsers();
        $chat->users()->attach([...$participantsIds]);

        return $chat;
    }


    /**
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        Chat::query()->find($id)->delete();
    }

    /**
     * @param int $id
     * @param int $userId
     * @return mixed
     */
    public function getParticipantsIdsByChatId(int $id, int $userId)
    {
        return Chat::query()->find($id)
            ->users
            ->except([$userId])
            ->pluck('id');
    }
}
