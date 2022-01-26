<?php

namespace App\Providers;

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
