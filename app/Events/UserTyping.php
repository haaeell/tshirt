<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserTyping implements ShouldBroadcast
{
    public $sender_id;
    public $receiver_id;

    public function __construct($sender_id, $receiver_id)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->receiver_id);
    }

    public function broadcastWith()
    {
        return [
            'sender_id' => $this->sender_id
        ];
    }
}
