<?php

namespace App\Services\Post;

use App\Events\AddedNewPost;
use App\Http\Resources\Api\User\PostCollection;
use App\Http\Resources\Api\User\PostResource;
use App\Models\Post;
use App\Repositories\Posts\PostRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostService
{
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository) {
        $this->postRepository = $postRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getAll(): AnonymousResourceCollection
    {
        return $this->postRepository->getAll();
    }

    /**
     * Get post by id.
     *
     * @param int $id
     * @return PostResource
     */
    public function findPost(int $id): PostResource
    {
        return $this->postRepository->find($id);
    }

    /**
     * @param Post $post
     * @return Post
     */
    public function likePost(Post $post): Post
    {
        $user = auth()->user();

        return $this->postRepository->like($post, $user);
    }

    /**
     * @param array $data
     * @return PostResource
     */
    public function storePost(array $data): PostResource
    {
        $user_id = auth()->user()->id;

        if ($data['image']) {
            $imagePath = $data['image']->storeAs(
                'posts_images',
                date('YmdHis') . '.' . $data['image']->extension(),
                'public'
            );
        }

        $data = [
            'user_id' => $user_id,
            'data' => [
                'text' => $data['text'],
                'image' => $imagePath ?? null
            ]
        ];

        $post = $this->postRepository->createFromArray($data);

        broadcast(new AddedNewPost($post->id));

        return $post;
    }

    /**
     * @param Post $post
     * @param array $data
     * @return void
     */
    public function updatePost(Post $post, array $data)
    {
        return $this->postRepository->updateFromArray($post, $data);
    }
}
