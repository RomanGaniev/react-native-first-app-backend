<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
// use App\Models\Api\v1\Comment;

class UserInfoResource extends JsonResource
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
            "avatar"            => asset( 'storage/' . $this->avatar ),
            "email"             => $this->email,
            "email_verified_at" => $this->email_verified_at,
            "photos"            => $this->photos,
            "remember_token"    => $this->remember_token,
            "created_at"        => $this->created_at,
            "updated_at"        => $this->updated_at,
        ];
    }
}
