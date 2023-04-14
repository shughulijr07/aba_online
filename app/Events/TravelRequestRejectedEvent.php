<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelRequestRejectedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $travel_request;
    public $reject_level;

    /**
     * Create a new event instance.
     *
     * @param $reject_level
     * @param $travel_request
     */
    public function __construct($reject_level,$travel_request)
    {
        //
        $this->travel_request = $travel_request;
        $this->reject_level = $reject_level;
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
