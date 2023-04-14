<?php

namespace App\Mail;


use App\Models\Staff;
use App\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public $recipient_type;
    public $recipient;
    public $staff_request;
    public $request_category;
    public $request_name;
    public $action_name;
    public $action;
    public $action_done_by_title;
    public $is_next_level_notification;
    public $new_supervisor_name = "";


    public function __construct($recipient_type,$recipient,$staff_request,$request_category, $request_name, $action_name,$action,$is_next_level_notification)
    {
        $this->recipient_type = $recipient_type;
        $this->staff_request = $staff_request;
        $this->recipient = $recipient;
        $this->request_category = $request_category;
        $this->request_name = $request_name;
        $this->action_name = $action_name;
        $this->action = $action;
        $this->is_next_level_notification = $is_next_level_notification;

        if(isset($action->level)){
            switch($action->level){
                case 'spv': $this->action_done_by_title = "Supervisor"; break;
                case 'hrm': $this->action_done_by_title = "Human Resource Manager"; break;
                case 'acc': $this->action_done_by_title = "Accountant"; break;
                case 'fd': $this->action_done_by_title = "Finance Director"; break;
                case 'md': $this->action_done_by_title = "Managing Director"; break;
                default: $this->action_done_by_title = ""; break;
            }
        }

        if($this->action_name == 'Changing Supervisor'){
            $newSupervisor = Staff::find($action->new_spv_id);
            $this->new_supervisor_name = ucwords(strtolower($newSupervisor->first_name.' '.$newSupervisor->last_name));
        }

    }


    public function build()
    {
        $email = $this->subject('PORTAL NOTIFICATION');
        switch ($this->action_name) {
            case 'Submitting': $email = $this->subject('NEW : '.$this->request_name.' Request #'.$this->staff_request->no)->markdown('emails.request.submission'); break;
            case 'Approving' : $email = $this->subject('APPROVAL : '.$this->request_name.' Request #'.$this->staff_request->no)->markdown('emails.request.approval'); break;
            case 'Returning' : $email = $this->subject('RETURN : '.$this->request_name.' Request #'.$this->staff_request->no)->markdown('emails.request.returning'); break;
            case 'Rejecting' : $email = $this->subject('REJECTION : '.$this->request_name.' Request #'.$this->staff_request->no)->markdown('emails.request.rejection'); break;
            case 'Changing Supervisor' : $email = $this->subject('SUPERVISOR CHANGE : '.$this->request_name.' Request #'.$this->staff_request->no)->markdown('emails.request.supervisor_change'); break;
            default: break;
        }

        return $email;
    }
}
