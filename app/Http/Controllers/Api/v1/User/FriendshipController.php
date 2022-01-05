<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\FriendRequestResource;
use App\Http\Resources\Api\User\UserProfileResource;
use App\Http\Resources\Api\User\SearchUsersResource;
use App\Models\Friendship;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        $friends = $user->friends->where('pivot.status', 'confirmed');

        return UserProfileResource::collection($friends);
    }

    public function showRequests(Request $request)
    {
        $user = auth()->user();

        $requests = $user->friend_requests;

        return FriendRequestResource::collection($requests);
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

        $friendship = $user->friend_requests->where('first_user', $otherUserId)->first();

        $friendship->acted_user = $user->id;
        $friendship->status = 'confirmed';
        $friendship->save();

        return response()->json(['message' => 'Запрос на дружбу принят']);
    }

    public function rejectOrCancelFriendRequest(Request $request, $otherUserId)
    {
        $user = auth()->user();

        $friendship = $user->friend_requests->where('first_user', $otherUserId)->first();

        $friendship->delete();

        return response()->json(['message' => 'Запрос на дружбу отклонен/Заявка отменена']);
    }

    public function remove(Request $request, $otherUserId)
    {
        $user = auth()->user();

        $friendship = $user->friends->find($otherUserId)->pivot;
        $friendship->delete();

        return response()->json(['message' => 'Пользователь с id = ' . $otherUserId . ' удален из друзей']);
    }

}
