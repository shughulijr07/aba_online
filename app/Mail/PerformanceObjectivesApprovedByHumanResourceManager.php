<?php

namespace App\Mail;

use App\PerformanceObjectives;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PerformanceObjectivesApprovedByHumanResourceManager extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $performance_objectives;
    /**
     * @var
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $performance_objectives
     */
    public function __construct($recipient_type,$recipient,$performance_objectives)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->performance_objectives = $performance_objectives;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.performance_objectives.performance_objectives_approved_by_hrm_email');
    }
}
