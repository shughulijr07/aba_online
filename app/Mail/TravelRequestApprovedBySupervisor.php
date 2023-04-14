<?php

namespace App\Mail;

use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TravelRequestApprovedBySupervisor extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $recipient_type;
    /**
     * @var
     */
    public $travel_request;
    /**
     * @var
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param $recipient_type
     * @param $recipient
     * @param $travel_request
     */
    public function __construct($recipient_type,$recipient,$travel_request)
    {
        //
        $this->recipient_type = $recipient_type;
        $this->travel_request = $travel_request;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.travel_requests.travel_request_approved_by_supervisor_email');
    }
}
