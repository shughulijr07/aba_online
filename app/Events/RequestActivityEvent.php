<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $staff_request;
    public $request_name;
    public $request_category;
    public $action_name;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param $staff_request
     * @param $request_name
     * @param $request_category
     * @param $action_name
     * @param $action
     */
    public function __construct($staff_request,$request_name,$request_category,$action_name,$action)
    {
        $this->staff_request = $staff_request;
        $this->request_name = $request_name;
        $this->request_category = $request_category;
        $this->action_name = $action_name;
        $this->action = $action;
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
