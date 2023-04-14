<?php

namespace App\Mail;

use App\PerformanceObjectives;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PerformanceObjectivesRejected extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $recipient;
    /**
     * @var
     */
    public $performance_objectives;
    /**
     * @var
     */
    public $reject_level;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $performance_objectives
     * @param $reject_level
     */
    public function __construct($recipient_type,$recipient,$performance_objectives,$reject_level)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->recipient = $recipient;
        $this->performance_objectives = $performance_objectives;
        $this->reject_level = $reject_level;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.performance_objectives.performance_objectives_rejected_email');
    }


}
