<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\UserProfileResource;
use App\Models\Friendship;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function friends(Request $request)
    {
        $user = auth()->user();

        $friends = $user->friends;

        return UserProfileResource::collection($friends);
    }

    public function sendFriendRequest(Request $request, $otherUserId)
    {
        $user = auth()->user();

        Friendship::create([
            'first_user' => $user->id,
            'second_user' => $otherUserId,
            'acted_user' => $user->id,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Запрос на дружбу отправлен']);
    }

    public function acceptFriendRequest(Request $request, $otherUserId)
    {
        $user = auth()->user();

        $friendship = $user->friend_requests->whereFirstUser($otherUserId)->first();

        $friendship->acted_user = $user->id;
        $friendship->status = 'confirm';
        $friendship->save();

        return response()->json(['message' => 'Запрос на дружбу принят']);
    }
}
