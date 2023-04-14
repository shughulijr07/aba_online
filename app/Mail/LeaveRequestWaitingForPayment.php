<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestWaitingForPayment extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $staff;
    /**
     * @var
     */
    public $supervisor;
    /**
     * @var
     */
    public $accountant;
    /**
     * @var
     */
    public $leave;

    /**
     * Create a new message instance.
     *
     * @param $staff
     * @param $supervisor
     * @param $accountant
     * @param $leave
     */
    public function __construct($staff,$supervisor,$accountant,$leave)
    {
        $this->staff = $staff;
        $this->supervisor = $supervisor;
        $this->accountant = $accountant;
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave.hrm_approval.accountant_email');
    }
}
