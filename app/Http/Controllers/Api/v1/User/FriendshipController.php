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
    public function getFriends(Request $request)
    {
        $user = auth()->user();

        $friends = $user->friends->where('pivot.status', 'confirmed');

        return UserProfileResource::collection($friends);
    }

    public function deleteFriend(Request $request, $friend_id)
    {
        $user = auth()->user();

        $friendship = $user->friends->find($friend_id)->pivot;
        $friendship->delete();

        return response()->json(['message' => 'Пользователь с id = ' . $friend_id . ' удален из друзей']);
    }

    public function getRequests(Request $request)
    {
        $user = auth()->user();

        $requests = $user->friend_requests;

        return FriendRequestResource::collection($requests);
    }

    public function createRequest(Request $request)
    {
        $user = auth()->user();
        $user_id = $request->get('user_id');

        Friendship::create([
            'first_user' => $user->id,
            'second_user' => $user_id,
            'acted_user' => $user->id,
            'status' => 'pending'
        ]);

        return response()->json(['message' => 'Запрос на дружбу отправлен']);
    }

    public function acceptRequest(Request $request, $user_id)
    {
        $user = auth()->user();

        $friendship = $user->friend_requests->where('first_user', $user_id)->first();

        $friendship->acted_user = $user->id;
        $friendship->status = 'confirmed';
        $friendship->save();

        return response()->json(['message' => 'Запрос на дружбу принят']);
    }

    public function deleteRequest(Request $request, $user_id)
    {
        $user = auth()->user();

        $friendship = $user->friend_requests->where('first_user', $user_id)->first();

        $friendship->delete();

        return response()->json(['message' => 'Запрос на дружбу отклонен/Заявка отменена']);
    }
}
