<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestApprovedByManagingDirector extends Mailable
{
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $leave;
    /**
     * @var
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $leave
     */
    public function __construct($recipient_type,$recipient,$leave)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->leave = $leave;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave.leave_request_approved_by_md_email');
    }
}
