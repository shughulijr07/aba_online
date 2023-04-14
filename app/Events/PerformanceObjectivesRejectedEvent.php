<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PerformanceObjectivesRejectedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $performance_objective;
    public $reject_level;

    /**
     * Create a new event instance.
     *
     * @param $reject_level
     * @param $performance_objective
     */
    public function __construct($reject_level,$performance_objective)
    {
        //
        $this->performance_objective = $performance_objective;
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
