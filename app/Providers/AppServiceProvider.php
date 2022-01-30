<?php

namespace App\Providers;

use App\Repositories\ChatMessages\ChatMessageRepositoryInterface;
use App\Repositories\ChatMessages\EloquentChatMessageRepository;
use App\Repositories\Chats\ChatRepositoryInterface;
use App\Repositories\Chats\EloquentChatRepository;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\EloquentCommentRepository;
use App\Repositories\Posts\EloquentPostRepository;
use App\Repositories\Posts\PostRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            PostRepositoryInterface::class,
            EloquentPostRepository::class
        );
        $this->app->bind(
            CommentRepositoryInterface::class,
            EloquentCommentRepository::class
        );
        $this->app->bind(
            ChatRepositoryInterface::class,
            EloquentChatRepository::class
        );
        $this->app->bind(
            ChatMessageRepositoryInterface::class,
            EloquentChatMessageRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
