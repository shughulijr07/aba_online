<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestRejected extends Mailable
{
    use Queueable, SerializesModels;
    use Queueable, SerializesModels;

    public $recipient_type;
    public $recipient;
    public $leave;
    public $reject_level;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $leave
     * @param $reject_level
     */
    public function __construct($recipient_type,$recipient,$leave,$reject_level)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->recipient = $recipient;
        $this->leave = $leave;
        $this->reject_level = $reject_level;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave.leave_request_rejected_email');
    }

    
}
