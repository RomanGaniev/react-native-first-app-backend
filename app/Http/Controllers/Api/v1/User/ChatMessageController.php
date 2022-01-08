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
    public function show(Chat $chat)
    {
        $this->readAllMessages($chat);

        $messages = $chat->messages;

        broadcast(new ChatsUpdated());
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

        broadcast(new ChatMessageSent($chat_id, $chat_message->id))->toOthers();
        broadcast(new ChatsUpdated());

        return 'Сообщение "' . $text . '" отправлено';
    }

    public function readAllMessages(Chat $chat)
    {
        $user = auth()->user(); //

        $messages = $chat->messages;
        foreach($messages as $message) {
            if ($message->user_id !== $user->id) {
                $message->read = true;
                $message->save();
            }
        }
    }

    public function readAllMessagesWhenLeavingChat(Chat $chat)
    {
        $this->readAllMessages($chat);

        broadcast(new ChatsUpdated());
    }
}
