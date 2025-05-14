<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagesRead
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $readerId;
    public $messageIds;

    public function __construct($chat, $readerId, $messageIds)
    {
        $this->chatId = $chat->id;
        $this->readerId = $readerId;
        $this->messageIds = $messageIds;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->chatId);
    }
}
