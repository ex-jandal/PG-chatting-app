<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDelete implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public $activeId, public $messageID) { }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->activeId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.delete';
    }
}
