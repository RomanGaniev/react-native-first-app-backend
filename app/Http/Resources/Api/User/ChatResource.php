<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"                    => $this->id,
            "name"                  => $this->name,
            "avatar"                => $this->avatar ? asset( 'storage/' . $this->avatar ) : null,
            "is_private"            => $this->is_private,
            "latest_message"        => new ChatMessageResource($this->latestMessage),
            "count_unread_messages" => $this->unreadMessages->count(),
            "participants_count"    => $this->users->count(),
            "interlocutor"          => $this->is_private ? new UserInfoResource($this->users->where('id', '!=', auth()->user()->id)->first()) : null,
            "created_at"            => $this->created_at
        ];
    }

    // public function showSystemMessage() {
    //     if ($this->latestMessage->user_id === auth()->user()->id) {
    //         return 'Вы создали беседу';
    //     } else {
    //         return 'Вы добавлены в беседу';
    //     }
    // }
}
