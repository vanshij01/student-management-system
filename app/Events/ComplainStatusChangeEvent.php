<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplainStatusChangeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $complain;
    public $studentData;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($studentData, $complain)
    {
        $this->complain = $complain;
        $this->studentData = $studentData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
