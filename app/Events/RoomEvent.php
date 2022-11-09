<?php

namespace App\Events;

use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomNum;
    public $message;
    public $channel;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($roomNum, $message)
    {
        $this->roomNum = $roomNum;
        $this->message = $message;
        $this->channel = 'room.'.$this->roomNum;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel($this->channel);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'channel' => $this->channel,
        ];
    }
}
