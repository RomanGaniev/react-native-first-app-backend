<?php

namespace App\Services\Post;

use App\Events\AddedNewPost;
use App\Events\PostLiked;
use App\Http\Resources\Api\User\PostResource;
use App\Repositories\Posts\PostRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostService
{
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository) {
        $this->postRepository = $postRepository;
    }

    /**
     * Get all posts.
     *
     * @return AnonymousResourceCollection
     */
    public function getAll(): AnonymousResourceCollection
    {
        $posts = $this->postRepository->getAll();

        return PostResource::collection($posts);
    }

    /**
     * Get post by id.
     *
     * @param int $id
     * @return PostResource
     */
    public function findPost(int $id): PostResource
    {
        $post = $this->postRepository->find($id);

        return new PostResource($post);
    }

    /**
     * Store post.
     *
     * @param array $data
     * @return PostResource
     */
    public function storePost(array $data): PostResource
    {
        // TODO: изолировать сохранение файлов в отдельный класс.
        if (isset($data['image'])) {
            $imagePath = $data['image']->storeAs(
                'posts_images',
                date('YmdHis') . '.' . $data['image']->extension(),
                'public'
            );
        }
        $array = [
            'user_id' => auth()->id(),
            'data' => [
                'text' => $data['text'] ?? null,
                'image' => $imagePath ?? null,
            ],
        ];

        $post = $this->postRepository->createFromArray($array);
        broadcast(new AddedNewPost($post->id));

        return new PostResource($post);
    }

    /**
     * Toggle like post.
     *
     * @param int $id
     * @return PostResource
     */
    public function likePost(int $id): PostResource
    {
        $post = $this->postRepository->find($id);
        $this->postRepository->like($post, auth()->id());
        broadcast(new PostLiked($id))->toOthers();

        return new PostResource($post);
    }
}
