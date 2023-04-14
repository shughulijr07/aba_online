<?php

namespace App\Mail;

use App\Models\TimeSheet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimeSheetRejected extends Mailable
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
    public $time_sheet;
    /**
     * @var
     */
    public $reject_level;
    public $months;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $time_sheet
     * @param $reject_level
     */
    public function __construct($recipient_type,$recipient,$time_sheet,$reject_level)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->recipient = $recipient;
        $this->time_sheet = $time_sheet;
        $this->reject_level = $reject_level;
        $this->months = TimeSheet::$months;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.time_sheets.time_sheet_rejected_email');
    }


}
