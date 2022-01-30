<?php

namespace App\Repositories\Chats;

use App\Http\Resources\Api\User\ChatResource;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface ChatRepositoryInterface
{
    public function getByUser(Authenticatable $user): AnonymousResourceCollection;

    public function createChatFromArray(array $data, array $participantsIds): ChatResource;

    public function find(int $id): ChatResource;

    public function updateFromArray(int $id, array $data, array $participantsIds);

    public function destroy(int $id): void;

    public function getParticipantsIdsByChatId(int $id, int $userId);
}
