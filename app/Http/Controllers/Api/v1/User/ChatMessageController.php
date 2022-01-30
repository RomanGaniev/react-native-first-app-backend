<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\Message\IndexMessageRequest;
use App\Http\Requests\Chat\Message\ShowMessageRequest;
use App\Http\Requests\Chat\Message\StoreMessageRequest;
use App\Services\ChatMessage\ChatMessageService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    private $chatMessageService;

    public function __construct(ChatMessageService $chatMessageService)
    {
        $this->chatMessageService = $chatMessageService;
    }

    /**
     * Get all chat messages by chat id.
     *
     * @param IndexMessageRequest $request
     * @param int $chatId
     * @return JsonResponse
     */
    public function index(IndexMessageRequest $request, int $chatId): JsonResponse
    {
        $messages = $this->chatMessageService->getAllMessagesByChatId($chatId);

        return response()->json($messages, 200);
    }

    /**
     * Send chat message.
     *
     * @param StoreMessageRequest $request
     * @param int $chatId
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request, int $chatId): JsonResponse
    {
        $data = $request->getFormData();
        $message = $this->chatMessageService->storeChatMessage($chatId, $data);

        return response()->json($message, 201);
    }

    /**
     * Find chat message.
     *
     * @param ShowMessageRequest $request
     * @param int $chatId
     * @param int $chatMessageId
     * @return JsonResponse
     */
    public function show(ShowMessageRequest $request, int $chatId, int $chatMessageId): JsonResponse
    {
        $message = $this->chatMessageService->findChatMessage($chatId, $chatMessageId);

        return response()->json($message, 200);
    }
}
