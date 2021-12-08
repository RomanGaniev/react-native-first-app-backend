<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Api\v1\Comment;

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
        // $user = auth()->user();
        // $like = Like::wherePostId($this->id)->whereUserId($user->id)->first();

        return [
            "id"                => $this->id,
            "title"             => $this->title ?? null,
            "created_at"        => $this->created_at,
            "commentator"       => [
                "first_name"    => $this->user ? $this->user->first_name : null,
                "last_name"     => $this->user ? $this->user->last_name : null,
                "avatar"        => $this->user ? asset( 'storage/' . $this->user->avatar )  : null
            ]
        ];
    }
}
