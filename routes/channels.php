<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use App\Broadcasting\RoomChannel;

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

Broadcast::channel('room.{room_number}', function () {
    return true;
});

