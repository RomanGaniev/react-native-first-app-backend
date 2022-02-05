<?php

namespace App\Services\Post;

use App\Events\AddedNewPost;
use App\Events\PostLiked;
use App\Http\Resources\Api\User\PostResource;
use App\Repositories\Posts\PostRepositoryInterface;
use App\Utils\ImageUploader;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostService
{
    private $postRepository;
    private $imageUploader;

    public function __construct(
        PostRepositoryInterface $postRepository,
        ImageUploader $imageUploader
    ) {
        $this->postRepository = $postRepository;
        $this->imageUploader = $imageUploader;
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
        if (isset($data['image'])) {
            $imagePath = $this->imageUploader
                ->upload('posts_images', $data['image']);
        }

        $post = $this->postRepository
            ->createFromArray([
                'user_id' => auth()->id(),
                'data' => [
                    'text' => $data['text'] ?? null,
                    'image' => $imagePath ?? null
                ]
            ]);

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
