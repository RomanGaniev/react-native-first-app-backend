<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\Comment;
use App\Services\Comment\CommentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function getByPostId(Request $request, int $id): JsonResponse
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->commentService->getByPostId($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    public function storeByPostId(Request $request, $post_id)
    {
        $data = $request->only([
            'title',
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->commentService->storeCommentByPostId($post_id, $data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
