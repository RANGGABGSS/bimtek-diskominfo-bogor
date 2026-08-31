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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin-only channel for real-time participant events
Broadcast::channel('admin.participants', function ($user) {
    return $user && $user->role === 'admin';
});

// Admin-only channel for specific BIMTEK participants
Broadcast::channel('admin.bimtek.{bimtekId}.participants', function ($user, $bimtekId) {
    return $user && $user->role === 'admin';
});
