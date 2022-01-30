<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\Comment\StoreCommentRequest;
use App\Services\Comment\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Get all comments of post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getByPostId(int $id): JsonResponse
    {
        $comments = $this->commentService->getByPostId($id);

        return response()->json($comments, 200);
    }

    /**
     * Add comment to post.
     *
     * @param StoreCommentRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function storeByPostId(StoreCommentRequest $request, $id): JsonResponse
    {
        $data = $request->only(['title']);
        $comment = $this->commentService->storeCommentByPostId($id, $data);

        return response()->json($comment, 201);
    }
}
