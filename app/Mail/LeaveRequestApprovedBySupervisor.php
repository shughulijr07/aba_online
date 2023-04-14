<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestApprovedBySupervisor extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    public $recipient;
    public $leave;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $leave
     */
    public function __construct($recipient_type,$recipient,$leave)
    {

        $this->recipient_type = $recipient_type;
        $this->recipient = $recipient;
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave.leave_request_approved_by_supervisor_email');
    }

}
