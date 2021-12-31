<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
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
            "_id"               => $this->id,
            "text"              => $this->text,
            "createdAt"         => $this->created_at,
            "chat_id"           => $this->chat_id,
            "user"              => [
                "_id"       => $this->user->id,
                "name"      => $this->user->first_name . ' ' . $this->user->last_name,
                "avatar"    => $this->user ? asset( 'storage/' . $this->user->avatar )  : null
            ],
        ];
    }
}
