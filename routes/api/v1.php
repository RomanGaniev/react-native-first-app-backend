<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\User\PostController;
use App\Http\Controllers\Api\v1\User\CommentController;
use App\Http\Controllers\Api\v1\User\ChatController;
use App\Http\Controllers\Api\v1\User\ChatMessageController;
use App\Http\Controllers\Api\v1\User\FriendshipController;
use App\Http\Controllers\Api\v1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->namespace('Auth')
    ->group(function () {
        Route::post('registration', [AuthController::class, 'registration']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    }
);

Route::prefix('user')
    ->middleware('auth:api')
    ->namespace('User')
    ->group(function () {
        Route::prefix('posts')->group(function () {
            Route::get('/', [PostController::class, 'index']);
            Route::get('/{postId}', [PostController::class, 'show']);
            Route::post('/', [PostController::class, 'store']);
            Route::patch('/{postId}/toggle_like', [PostController::class, 'like']);

            Route::get('/{postId}/comments', [CommentController::class, 'getByPostId']);
            Route::post('/{postId}/comments', [CommentController::class, 'storeByPostId']);
        });

        Route::prefix('chats')->group(function () {
            Route::get('/', [ChatController::class, 'index']);
            Route::get('/{chatId}', [ChatController::class, 'show']);
            Route::post('/', [ChatController::class, 'store']);
//            Route::post('/{interlocutor_id}', [ChatController::class, 'createPrivate']);
            Route::patch('/{chatId}', [ChatController::class, 'update']);
            Route::delete('/{chatId}', [ChatController::class, 'destroy']);
            Route::get('/{chatId}/participants', [ChatController::class, 'getParticipantsIds']);




            Route::get('/{chatId}/messages', [ChatMessageController::class, 'index']);
            Route::get('/{chatId}/messages/{chatMessageId}', [ChatMessageController::class, 'show']);
            Route::post('/{chatId}/messages', [ChatMessageController::class, 'store']);



        });

        Route::prefix('friendship')->group(function () {
            Route::get('/friends', [FriendshipController::class, 'getFriends']);
            Route::delete('/friends/{friend_id}', [FriendshipController::class, 'deleteFriend']);

            Route::get('/requests', [FriendshipController::class, 'getRequests']);
            Route::post('/requests', [FriendshipController::class, 'createRequest']);
            Route::put('/requests/{user_id}', [FriendshipController::class, 'acceptRequest']);
            Route::delete('/requests/{user_id}', [FriendshipController::class, 'deleteRequest']);
        });

        // TODO: переделать
        Route::get('search/users', [UserController::class, 'show']);
    }
);

