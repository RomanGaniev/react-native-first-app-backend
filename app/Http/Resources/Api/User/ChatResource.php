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
            "is_private"            => $this->is_private,
            "latest_message"        => $this->latestMessage,
            "participants_count"    => $this->users->count(),
            "interlocutor"          => $this->is_private ? new UserInfoResource($this->users->where('id', '!=', auth()->user()->id)->first()) : null,
            "created_at"            => $this->created_at
        ];
    }
}
