<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->namespace('Auth')
    ->group(function () {
        Route::post('registration', 'AuthController@registration');
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('me', 'AuthController@me');
    }
);

Route::prefix('user')
    ->middleware('auth:api')
    ->namespace('User')
    ->group(function () {
        Route::prefix('posts')->group(function () {
            Route::get('/', 'PostController@index');
            Route::get('/{id}', 'PostController@show');
            Route::post('/', 'PostController@store');

            Route::post('/{post}/toggle_like', 'PostController@like');
            Route::get('/{id}/comments', 'CommentController@getByPostId');
            Route::post('/{id}/comments', 'CommentController@storeByPostId');
        });

        Route::prefix('chats')->group(function () {
            Route::get('/', 'ChatController@getChats');
            Route::get('/{chat}', 'ChatController@detailt');
            Route::post('/', 'ChatController@createGroup');
            Route::post('/{interlocutor_id}', 'ChatController@createPrivate');
            Route::put('/{chat}', 'ChatController@edit');
            Route::delete('/{chat}', 'ChatController@delete');

            Route::get('/{chat}/messages', 'ChatMessageController@getMessages');
            Route::get('/{chat}/messages/{chat_message}', 'ChatMessageController@detailt');
            Route::post('/{chat}/messages', 'ChatMessageController@create');
            Route::get('/{chat}/participants', 'ChatController@getParticipants');
        });

        Route::prefix('friendship')->group(function () {
            Route::get('/friends', 'FriendshipController@getFriends');
            Route::delete('/friends/{friend_id}', 'FriendshipController@deleteFriend');

            Route::get('/requests', 'FriendshipController@getRequests');
            Route::post('/requests', 'FriendshipController@createRequest');
            Route::put('/requests/{user_id}', 'FriendshipController@acceptRequest');
            Route::delete('/requests/{user_id}', 'FriendshipController@deleteRequest');
        });

        // TODO: переделать
        Route::get('search/users', 'UserController@show');
    }
);

