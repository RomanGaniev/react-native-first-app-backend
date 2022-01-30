<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user is a member of the chat.
     *
     * @param User $user
     * @param Chat $chat
     * @return bool
     */
    public function view(User $user, Chat $chat): bool
    {
        $chatUsers = $chat->users()->get();

        return $chatUsers->contains($user->id);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Chat $chat
     * @return bool
     */
    public function update(User $user, Chat $chat): bool
    {
        return $user->id === $chat->users()->first()->id;
    }

    /**
     * Determine whether the user can destroy the model.
     *
     * @param User $user
     * @param Chat $chat
     * @return bool
     */
    public function destroy(User $user, Chat $chat): bool
    {
        return $user->id === $chat->users()->first()->id;
    }
}
