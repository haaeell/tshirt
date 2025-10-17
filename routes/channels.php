<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{userId}', function ($user, $userId) {
        return (int)$user->id === (int)$userId;
});

Broadcast::channel('presence.online', function ($user) {
        return ['id' => $user->id, 'name' => $user->nama ?? $user->name];
});
