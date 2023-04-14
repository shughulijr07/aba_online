<?php

namespace App\Mail;

use App\Models\LeavePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeavePlanApprovedByManagingDirector extends Mailable
{
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

    public $months;

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
        return $this->markdown('emails.leave_plans.leave_plan_approved_by_md_email');
    }
}
