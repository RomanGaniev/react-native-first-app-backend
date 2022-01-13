<?php

namespace App\Http\Resources\Api\User;

use App\Models\Api\v1\Chat\Chat;
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
            "text"              => $this->system 
                                        ? 
                                            ( 
                                                $this->user_id === auth()->user()->id 
                                                    ? 'Вы создали беседу '
                                                    : 'Вас добавили в беседу'
                                            )
                                        : $this->text,
            "createdAt"         => $this->created_at,
            "chat_id"           => $this->chat_id,
            "user"              => [
                "_id"       => $this->user->id,
                "name"      => $this->user->first_name . ' ' . $this->user->last_name,
                "avatar"    => $this->user ? asset( 'storage/' . $this->user->avatar )  : null
            ],
            "read"              => $this->read,
            "system"            => $this->system
        ];
    }
}
