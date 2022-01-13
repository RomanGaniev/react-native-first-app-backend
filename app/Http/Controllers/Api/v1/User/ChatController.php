<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\ChatResource;
use App\Http\Resources\Api\User\UserInfoResource;
use App\Models\Api\v1\Chat\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Api\v1\Chat\ChatMessage;
use App\Events\ChatsUpdated;
use App\Models\User;

class ChatController extends Controller
{
    public function getChats(Request $request)
    {
        $user = auth()->user();

        $chats = $user->chats->sortByDesc('latestMessage.created_at');

        return ChatResource::collection($chats);
    }

    public function detailt(Request $request, Chat $chat)
    {
        return new ChatResource($chat);
    }

    public function createGroup(Request $request)
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

        ChatMessage::create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'text' => 'created_group_chat',
            'system' => true
        ]);

        broadcast(new ChatsUpdated());

        return new ChatResource($chat);
    }

    public function createPrivate(Request $request, $interlocutor_id)
    {
        $user = auth()->user();

        $chatId = null;
        $chats = Chat::with('users')->whereIsPrivate(true)->get();

        foreach($chats as $chat) {
            if ($chat->users->contains($user->id) && $chat->users->contains($interlocutor_id)) {
                $chatId = $chat->id;
            }
        }

        // если чат уже есть, то возвращаем его
        if($chatId) {
            return new ChatResource(Chat::find($chatId));
        } else { // иначе создаем чат
            $chat = Chat::create([
                'is_private' => true
            ]);
    
            $chat->users()->attach([$user->id, $interlocutor_id]);

            ChatMessage::create([
                'user_id' => $user->id,
                'chat_id' => $chat->id,
                'text' => 'created_private_chat',
                'system' => true
            ]);

            broadcast(new ChatsUpdated());
    
            return new ChatResource($chat);
        }
    }

    public function edit(Request $request, Chat $chat)
    {
        $user = auth()->user();

        // $fd = $request->get('chatName');
        // dd($fd);


        $chatName       = $request->get('chatName');
        $avatar     = $request->file('avatar');
        // $chatId     = $request->get('chatId');
        // dd($request);
        $friends    = $request->get('friends', []);

        $avatarPath = null;
        if($avatar) {
            $randomAvatarName = Str::random(10);
            $avatarPath = $avatar->storeAs(
                'chat_avatars',
                $randomAvatarName . '_' . $chatName . '.' . $avatar->extension(),
                'public'
            );
        }

        // $chat = Chat::find($chat);
        // dd($chatName);
        $chat->name = $chatName;
        if($avatarPath) {
            $chat->avatar = $avatarPath;
        }
        $chat->save();

        $chat->detachAllUsers();
        $chat->users()->attach([$user->id, ...$friends]);

        broadcast(new ChatsUpdated());

        return new ChatResource($chat);
    }

    public function delete(Request $request, Chat $chat)
    {
        $chat->delete();
        broadcast(new ChatsUpdated());
    }

    public function getParticipants(Chat $chat)
    {
        $user = auth()->user();
        $participantsIds = $chat->users->except([$user->id])->pluck('id');
        return $participantsIds;
    }
}
