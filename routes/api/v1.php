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
        Route::post('post/create', 'PostController@create');
        Route::post('posts/{post}/like', 'PostController@like');
        Route::get('posts', 'PostController@show');
        Route::get('posts/{post}', 'PostController@showOne');
        Route::get('posts/{post}/comments', 'PostController@getComments');
        Route::post('posts/add_comment', 'PostController@addComment');

        Route::get('chats', 'ChatController@show');
        Route::post('chats/{interlocutorId}/create_private', 'ChatController@createPrivate');
        Route::post('chats/create_general', 'ChatController@createGeneral');
        Route::post('chats/edit_general', 'ChatController@editGeneral');
        Route::post('chats/{chatId}/delete_general', 'ChatController@deleteGeneral');
        Route::get('chats/{chat}/messages', 'ChatMessageController@show');
        Route::post('chats/send_message', 'ChatMessageController@send');
        Route::get('chats/{chat_message}', 'ChatMessageController@showOne');

        Route::get('chats/{chat}/participants', 'ChatController@showParticipants');

        Route::get('friends', 'FriendshipController@show');
        Route::get('friends/requests', 'FriendshipController@showRequests');
        Route::post('friends/{otherUserId}/send_request', 'FriendshipController@sendFriendRequest');
        Route::post('friends/{otherUserId}/accept_request', 'FriendshipController@acceptFriendRequest');
        Route::post('friends/{otherUserId}/reject_or_cancel_request', 'FriendshipController@rejectOrCancelFriendRequest');
        Route::post('friends/{otherUserId}/remove', 'FriendshipController@remove');

        Route::get('search/users', 'UserController@show');
    }
);

