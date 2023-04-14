<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PerformanceObjectivesApprovedBySupervisorEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $performance_objective;

    /**
     * Create a new event instance.
     *
     * @param $performance_objective
     */
    public function __construct($performance_objective)
    {
        //
        $this->performance_objective = $performance_objective;
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
