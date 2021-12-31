<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\ChatResource;
use App\Models\Api\v1\Chat\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        $chats = $user->chats;

        return ChatResource::collection($chats);
    }
}
