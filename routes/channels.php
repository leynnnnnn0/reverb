<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('order.{user_id}', function (User $user, $user_id){
    return $user->id === (int) $user_id;
});


Broadcast::channel('whiteboard', function () {
   return true;
});
