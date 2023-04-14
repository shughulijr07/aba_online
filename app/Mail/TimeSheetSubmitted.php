<?php

namespace App\Mail;


use App\Models\TimeSheet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TimeSheetSubmitted extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $time_sheet;
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
     * @param $time_sheet
     */
    public function __construct($recipient_type,$recipient,$time_sheet)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->time_sheet = $time_sheet;
        $this->recipient = $recipient;
        $this->months = TimeSheet::$months;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.time_sheets.time_sheet_submitted_email');
    }
}
