<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Events\ChatsUpdated;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\ChatMessageResource;
use App\Models\Api\v1\Chat\Chat;
use App\Models\Api\v1\Chat\ChatMessage;
use Illuminate\Http\Request;

use App\Events\ChatMessageSent;

class ChatMessageController extends Controller
{
    public function getMessages(Chat $chat)
    {
        $user = auth()->user();

        $chat->readAllMessagesForUser($user);

        broadcast(new ChatsUpdated());

        return ChatMessageResource::collection($chat->messages);
    }

    public function detailt(Request $request, Chat $chat, ChatMessage $chat_message)
    {
        // $chat->messages->find()
        $chat_message->read();

        return new ChatMessageResource($chat_message);
    }

    public function create(Request $request, Chat $chat)
    {
        $user = auth()->user();
        $text = $request->get('text');

        $chat_message = ChatMessage::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'text' => $text
        ]);

        broadcast(new ChatMessageSent($chat->id, $chat_message->id))->toOthers();
        broadcast(new ChatsUpdated());

        return 'Сообщение "' . $text . '" отправлено';
    }
}
