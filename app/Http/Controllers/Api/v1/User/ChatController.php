<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\ChatResource;
use App\Http\Resources\Api\User\UserInfoResource;
use App\Models\Api\v1\Chat\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();

        $chats = $user->chats;

        return ChatResource::collection($chats);
    }

    public function createPrivate(Request $request, $interlocutorId)
    {
        $user = auth()->user();

        $authUser = null;
        $interlocutor = null;
        $chatId = null;
        $chats = Chat::with('users')->whereIsPrivate(true)->get();

        // ищем приватный чат с этими пользователями
        foreach($chats as $chat) {
            $authUser = $chat->users->find($user->id);
            $interlocutor = $chat->users->find($interlocutorId);
            if($authUser && $interlocutor) {
                $chatId = $chat->id;
            }
        }

        // если чат уже есть, то возвращаем его
        if($authUser && $interlocutor) {
            return new ChatResource(Chat::find($chatId));
        } else { // иначе создаем чат
            $chat = Chat::create([
                'is_private' => true
            ]);
    
            $chat->users()->attach([$user->id, $interlocutorId]);
    
            return new ChatResource($chat);
        }
    }

    public function createGeneral(Request $request)
    {
        $user = auth()->user();

        $avatar     = $request->file('avatar');
        $chatName   = request('chatName');
        $friends    = request('friends', []);

        $avatarPath = null;
        if($avatar) {
            $randomAvatarName = Str::random(10);
            $avatarPath = $avatar->storeAs(
                'chat_avatars',
                $randomAvatarName . '_' . $chatName . '.' . $avatar->extension(),
                'public'
            );
        }

        $chat = new Chat;
        $chat->avatar = $avatarPath;
        $chat->name = $chatName;
        $chat->is_private = false;
        $chat->save();

        $chat->users()->attach([$user->id, ...$friends]);

        return new ChatResource($chat);
    }

    public function editGeneral(Request $request)
    {
        $user = auth()->user();
        
        $avatar     = $request->file('avatar');
        $chatId     = request('chatId');
        $chatName   = request('chatName');
        $friends    = request('friends', []);

        $avatarPath = null;
        if($avatar) {
            $randomAvatarName = Str::random(10);
            $avatarPath = $avatar->storeAs(
                'chat_avatars',
                $randomAvatarName . '_' . $chatName . '.' . $avatar->extension(),
                'public'
            );
        }

        $chat = Chat::find($chatId);
        $chat->name = $chatName;
        if($avatarPath) {
            $chat->avatar = $avatarPath;
        }
        $chat->save();

        $chat->detachAllUsers();
        $chat->users()->attach([$user->id, ...$friends]);

        // return new ChatResource($chat);
    }

    public function deleteGeneral(Request $request, $chatId)
    {
        Chat::find($chatId)->delete();
    }

    public function showParticipants(Chat $chat)
    {
        $user = auth()->user();
        $participantsIds = $chat->users->except([$user->id])->pluck('id');
        return $participantsIds;
    }
}
