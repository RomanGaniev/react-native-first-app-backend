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
        Route::post('me', 'AuthController@me');
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
        Route::post('send_image', 'PostController@sendImage');
        Route::get('get_image', 'PostController@getImage');
        Route::get('posts/{post}/comments', 'PostController@getComments');
        Route::post('posts/add_comment', 'PostController@addComment');
    }
);

