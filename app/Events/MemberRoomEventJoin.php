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

class MemberRoomEventJoin implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Room $room, $message)
    {
        $this->room = $room;
        $this->message = $message;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('room.'.$this->room->room_number);
    }

    public function broadcastWith()
    {
        return [
            'room_number' => $this->room->room_number,
            'room_key' => $this->room->room_key,
            'join_name' => $this->room->join_name,
            'host_name' => $this->room->host_name,
            'message' => $this->message,
            'channel' => 'room.'.$this->room->room_number,
        ];
    }
}
