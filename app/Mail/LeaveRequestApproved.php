<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeaveRequestApproved extends Mailable
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
    public $hrm;
    /**
     * @var
     */
    public $leave;

    /**
     * Create a new message instance.
     *
     * @param $staff
     * @param $supervisor
     * @param $hrm
     * @param $leave
     */
    public function __construct($staff,$supervisor,$hrm,$leave)
    {
        $this->staff = $staff;
        $this->supervisor = $supervisor;
        $this->hrm = $hrm;
        $this->leave = $leave;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.leave.hrm_approval.hrm_email');
    }
}
