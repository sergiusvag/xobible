<?php
 
namespace App\Events;
 
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
 
class UserLoggedStatusUpdate implements ShouldBroadcast
{
    /**
     * The order instance.
     *
     * @var \App\User
     */
    public $user;
    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 
    * Get the channels the event should broadcast on.
    *
    * @return \Illuminate\Broadcasting\PrivateChannel
    */
    public function broadcastOn()
    {
        return ['chat'];
    }
    
    public function broadcastAs()
    {
        return 'logged';
    }
}