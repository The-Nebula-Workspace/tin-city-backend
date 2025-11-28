<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channel for route-specific bus tracking
Broadcast::channel('route.{routeId}', function () {
    return true; // Public channel - anyone can listen
});

Broadcast::channel('bus-chat.{busId}', function ($user, $busId) {
    return true;
});
