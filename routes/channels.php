<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::routes(['middleware' => ['jwt.auth']]); //if you use JWT

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    if ($user->canJoinChat($chatId)) {
        return $user;
    }
});

// Broadcast::channel('chat-channel', function ($user) {
//     return $user;
// });

// Broadcast::channel('user.{userId}', function ($user, $userId) {
//     if ($user->id === $userId) {
//       return array('name' => $user->name);
//     }
// });

Broadcast::channel('post-channel', function ($user) {
    return true;
});
// Broadcast::channel('post-channel', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });