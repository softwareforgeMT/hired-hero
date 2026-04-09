<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $title;
    public $user_id;
    // public $username;
    public function __construct($title,$user_id)
    {
        // $this->username = $username;
        $this->title  = $title;
        $this->user_id  = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new Channel('notifications');
    // }

    public function broadcastOn()
    {
        return new Channel('notifications'.$this->user_id);
    }

     // public function broadcastOn()
     //  {
     //      return ['my-channel'];
     //  }

      // public function broadcastAs()
      // {
      //     return 'my-event';
      // }
}
