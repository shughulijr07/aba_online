<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelRequestReturnedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $travel_request;
    public $return_level;

    /**
     * Create a new event instance.
     *
     * @param $return_level
     * @param $travel_request
     */
    public function __construct($return_level, $travel_request)
    {
        //
        $this->travel_request = $travel_request;
        $this->return_level = $return_level;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
