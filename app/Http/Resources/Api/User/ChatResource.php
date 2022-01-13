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
            "avatar"                => $this->avatar
                                            ?
                                                asset('storage/' . $this->avatar)
                                            :
                                                null,
            "latest_message"        => new ChatMessageResource($this->latestMessage),
            "is_private"            => $this->is_private,
            "interlocutor"          => $this->is_private
                                            ?
                                                new UserInfoResource(
                                                    $this->users
                                                        ->except(auth()->user()->id)
                                                        ->first()
                                                )
                                            :
                                                null,
            "participants_count"    => $this->users->count(),
            "unread_messages_count" => $this->unreadMessages->count(),
            "created_at"            => $this->created_at
        ];
    }
}
