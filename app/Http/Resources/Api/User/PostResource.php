<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->user();

        $createdAt = new Carbon($this->created_at);

        return [
            "id"                => $this->id,
            "data"  => [
                "text" => $this->data['text'] ? $this->data['text'] : null,
                "image" => $this->data['image'] ? asset('storage/' . $this->data['image']) : null
            ],
            "created_at"        => $createdAt->fromNow(),
            "author"            => [
                "first_name"        => $this->publisher ? $this->publisher->first_name : null,
                "last_name"         => $this->publisher ? $this->publisher->last_name : null,
                "avatar"            => $this->publisher ? asset('storage/' . $this->publisher->avatar)  : null
            ],
            "likes_count"       => $this->likes->count(),
            "liked"             => $this->likes->contains($user->id),
            "comments_count"    => $this->comments->count(),
            "views"             => $this->views
        ];
    }
}
