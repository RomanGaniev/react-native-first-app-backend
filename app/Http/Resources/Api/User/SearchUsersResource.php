<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Api\v1\Comment;

class SearchUsersResource extends JsonResource
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
            "id"                => $this->id,
            "uuid"              => $this->uuid,
            "first_name"        => $this->first_name,
            "last_name"         => $this->last_name,
            "email"             => $this->email,
            "avatar"            => asset( 'storage/' . $this->avatar ),

            "friendship"        => $this->friends->only([auth()->user()->id])->first() ? $this->friends->only([auth()->user()->id])->first()->pivot : null,
            // "friendship"        => $this->pivot,
        ];
    }
}
