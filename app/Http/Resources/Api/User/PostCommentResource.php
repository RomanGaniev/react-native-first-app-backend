<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Api\v1\Comment;
use Carbon\Carbon;

class PostCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $createdAt = new Carbon($this->created_at);

        return [
            "id"                => $this->id,
            "title"             => $this->title ?? null,
            "created_at"        => $createdAt->fromNow(),
            "commentator"       => [
                "first_name"    => $this->user ? $this->user->first_name : null,
                "last_name"     => $this->user ? $this->user->last_name : null,
                "avatar"        => $this->user ? asset('storage/' . $this->user->avatar)  : null
            ]
        ];
    }
}
