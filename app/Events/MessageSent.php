<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    public function __construct(public Chat $chat) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chat->receiver_id),
            new PrivateChannel('chat.' . $this->chat->sender_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->chat->id,
            'sender_id'   => $this->chat->sender_id,
            'receiver_id' => $this->chat->receiver_id,
            'message'     => $this->chat->message,
            'is_read'     => (bool)$this->chat->is_read,
            'created_at'  => $this->chat->created_at->toISOString(),
        ];
    }
}
