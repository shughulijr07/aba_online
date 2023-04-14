<?php

namespace App\Mail;



use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeavePlanSubmitted extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $leave_plan;
    /**
     * @var
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $leave_plan
     */
    public function __construct($recipient_type,$recipient,$leave_plan)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->leave_plan = $leave_plan;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave_plans.leave_plan_submitted_email');
    }
}
