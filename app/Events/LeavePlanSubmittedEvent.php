<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeavePlanSubmittedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $leave_plan;

    /**
     * Create a new event instance.
     *
     * @param $leave_plan
     */
    public function __construct($leave_plan)
    {
        //
        $this->leave_plan = $leave_plan;
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
