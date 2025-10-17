<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageRead implements ShouldBroadcastNow
{
    public function __construct(public int $readerId, public int $partnerId) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('chat.' . $this->partnerId)];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }

    public function broadcastWith(): array
    {
        return ['reader_id' => $this->readerId];
    }
}
