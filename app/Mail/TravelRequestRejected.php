<?php

namespace App\Mail;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TravelRequestRejected extends Mailable
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
    public $travel_request;
    /**
     * @var
     */
    public $reject_level;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $travel_request
     * @param $reject_level
     */
    public function __construct($recipient_type,$recipient,$travel_request,$reject_level)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->recipient = $recipient;
        $this->travel_request = $travel_request;
        $this->reject_level = $reject_level;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.travel_requests.travel_request_rejected_email');
    }


}
