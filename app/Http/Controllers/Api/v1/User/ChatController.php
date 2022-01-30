<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\DestroyChatRequest;
use App\Http\Requests\Chat\ShowChatRequest;
use App\Http\Requests\Chat\StoreChatRequest;
use App\Http\Requests\Chat\UpdateChatRequest;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Get all chats of user.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $chats = $this->chatService->getAll();

        return response()->json($chats, 200);
    }

    /**
     * Store chat.
     *
     * @param StoreChatRequest $request
     * @return JsonResponse
     */
    public function store(StoreChatRequest $request): JsonResponse
    {
        $data = $request->getFormData();
        $chat = $this->chatService->storeChat($data);

        return response()->json($chat, 201);
    }

    /**
     * Find chat.
     *
     * @param ShowChatRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(ShowChatRequest $request, int $id): JsonResponse
    {
        $chat = $this->chatService->findChat($id);

        return response()->json($chat, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateChatRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateChatRequest $request, int $id): JsonResponse
    {
        $data = $request->getFormData();
        $chat = $this->chatService->updateChat($id, $data);

        return response()->json($chat, 200);
    }

    /**
     * Destroy chat.
     *
     * @param DestroyChatRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(DestroyChatRequest $request, int $id): JsonResponse
    {
        $this->chatService->destroyChat($id);

        return response()->json('Chat deleted successfully.', 200);
    }

    /**
     * Get participants ids of chat.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getParticipantsIds(int $id): JsonResponse
    {
        $participantsIds = $this->chatService->getParticipantsIdsByChatId($id);

        return response()->json($participantsIds, 200);
    }
}
