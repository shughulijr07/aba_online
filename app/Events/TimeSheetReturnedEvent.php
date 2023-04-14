<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TimeSheetReturnedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $time_sheet;
    public $return_level;

    /**
     * Create a new event instance.
     *
     * @param $return_level
     * @param $time_sheet
     */
    public function __construct($return_level, $time_sheet)
    {
        //
        $this->time_sheet = $time_sheet;
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
