<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Api\v1\Comment;

class FriendRequestResource extends JsonResource
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
            "user"              => new UserInfoResource($this->firstUser),
            "status"            => $this->status,

            "created_at"        => $this->created_at,
            "updated_at"        => $this->updated_at,
        ];
    }
}
