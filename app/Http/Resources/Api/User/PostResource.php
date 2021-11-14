<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Api\v1\Like;

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
        $like = Like::wherePostId($this->id)->whereUserId($user->id)->first();
        // if($like) {
        //     return true;
        // } else {
        //     return false;
        // }

        return [
            "id"                => $this->id,
            "data"  => [
                "text" => $this->data['text'] ? $this->data['text'] : null,
                "image" => asset( 'storage/' . $this->data['image'] )
            ],
            "created_at"        => $this->created_at,
            "author"            => [
                "first_name"    => $this->publisher ? $this->publisher->first_name : null,
                "last_name"     => $this->publisher ? $this->publisher->last_name : null,
                "avatar"        => $this->publisher ? asset( 'storage/' . $this->publisher->avatar )  : null
            ],
            "likes_count"       => $this->likes->count(),
            "liked"             => $like ? true : false,
            "comments_count"    => $this->comments->count(),
            // "reposts"           => $this->reposts
        ];
    }
}
