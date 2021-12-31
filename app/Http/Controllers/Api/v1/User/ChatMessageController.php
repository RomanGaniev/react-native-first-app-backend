<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\ChatMessageResource;
use App\Models\Api\v1\Chat\Chat;
use App\Models\Api\v1\Chat\ChatMessage;
use Illuminate\Http\Request;

use App\Events\ChatMessageSent;

class ChatMessageController extends Controller
{
    public function show(Chat $chat)
    {
        $messages = $chat->messages;
        return ChatMessageResource::collection($messages);
    }

    public function showOne(Request $request, ChatMessage $chat_message)
    {
        return new ChatMessageResource($chat_message);
    }

    public function send(Request $request)
    {
        $user = auth()->user();
        $chat_id = $request->get('chat_id');
        $text = $request->get('text');

        $chat_message = ChatMessage::create([
            'user_id' => $user->id,
            'chat_id' => $chat_id,
            'text' => $text
        ]);

        broadcast(new ChatMessageSent($chat_message->id))->toOthers();

        return 'Сообщение "' . $text . '" отправлено';
    }
}
