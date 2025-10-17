<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserTypingEvent implements ShouldBroadcastNow
{
    public function __construct(public int $senderId, public int $receiverId) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->receiverId)];
    }

    public function broadcastAs(): string
    {
        return 'user.typing';
    }

    public function broadcastWith(): array
    {
        return ['sender_id' => $this->senderId];
    }
}
