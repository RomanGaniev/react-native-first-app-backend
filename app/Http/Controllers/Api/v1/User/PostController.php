<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Services\Post\PostService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Get all posts.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = $this->postService->getAll();

        return response()->json($posts, 200);
    }

    /**
     * Publish post.
     *
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->getFormData();
        $post = $this->postService->storePost($data);

        return response()->json($post, 201);
    }

    /**
     * Find post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = $this->postService->findPost($id);

        return response()->json($post, 200);
    }

    /**
     * Like post.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function like(int $id): JsonResponse
    {
        $post = $this->postService->likePost($id);

        return response()->json($post, 200);
    }
}
