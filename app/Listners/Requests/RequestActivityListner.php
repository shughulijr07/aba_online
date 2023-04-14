<?php

namespace App\Listners\Requests;

use App\Events\RequestActivityEvent;
use App\Mail\RequestActivityMail;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RequestActivityListner implements ShouldQueue
{
    public $failOnTimeout = false;
    public $timeout = 120000;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public $staff_request = null;
    public $request_category = null;
    public $request_name = null;
    public $action_name = null;
    public $action = null;
    public $is_next_level_notification = false;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RequestActivityEvent  $event
     * @return void
     */
    public function handle(RequestActivityEvent $event)
    {
        $this->staff_request = $event->staff_request;
        $this->request_category = $event->request_category;
        $this->request_name = $event->request_name;
        $this->action_name = $event->action_name;
        $this->action = $event->action;

        if($this->action_name == "Submitting"){
            $this->sendEmailToStaff();
            $this->sendEmailToSupervisor();
        }

        if($this->action_name == "Changing Supervisor"){
            $this->sendEmailToStaff();
            $this->sendEmailToSupervisor();
        }

        if(in_array($this->action_name, ["Approving", "Rejecting", "Returning"])){
            //Notify Staff
            $this->sendEmailToStaff();

            //Notify User who has done this action
            switch ($this->action->level) {
                case 'spv': $this->sendEmailToSupervisor(); break;
                case 'hrm': $this->sendEmailToHRM(); break;
                case 'acc': $this->sendEmailToAccountants(); break;
                case 'fd': $this->sendEmailToFinanceDirector(); break;
                case 'md': $this->sendEmailToManagingDirector(); break;
            }

            //Notify User responsible for approving in next level
            if(isset($this->action->notify_next_level)){
                if($this->action->notify_next_level == "yes"){
                    $this->is_next_level_notification = true;
                    switch ($this->action->next_level) {
                        case 'spv': $this->sendEmailToSupervisor(); break;
                        case 'hrm': $this->sendEmailToHRM(); break;
                        case 'acc': $this->sendEmailToAccountants(); break;
                        case 'fd': $this->sendEmailToFinanceDirector(); break;
                        case 'md': $this->sendEmailToManagingDirector(); break;
                    }
                }
            }
        }
    }


    public function sendEmailToStaff(){
        $staff = $this->staff_request->staff;
        if(isset($staff->id)){
            $this->sendEmail('staff', $staff);
        }
    }


    public function sendEmailToSupervisor(){
        $supervisor = Staff::find($this->staff_request->responsible_spv);
        if(isset($supervisor->id)){
            $this->sendEmail('spv', $supervisor);
        }
    }


    public function sendEmailToHRM(){
        //get ACCs
        $user = User::where('role_id','=','3')->where('status','active')->orderBy('id','DESC')->first();
        $hrm = $user->staff;
        if(isset($hrm->id)){
            $this->sendEmail('hrm', $hrm);
        }
    }



    public function sendEmailToAccountants(){
        //get ACCs
        $users = User::where('role_id','=','4')->where('status','active')->get(); // 4 is role id for ACCs
        if( count($users)>0 ){
            foreach ($users as $user){
                $acc = $user->staff;
                if(isset($acc->id)){
                    $this->sendEmail('acc', $acc);
                }
            }
        }
    }


    public function sendEmailToFinanceDirector(){
        //get FD
        $user = User::where('role_id','=','9')->where('status','active')->orderBy('id','DESC')->first(); //9 is role id for FD
        $fd = $user->staff;
        if(isset($fd->id)){
            $this->sendEmail('fd', $fd);
        }
    }


    public function sendEmailToManagingDirector(){
        $user = User::where('role_id','=','2')->where('status','active')->orderBy('id','DESC')->first(); // 2 is role id for MDs
        $md = $user->staff;
        if(isset($md->id)){
            $this->sendEmail('md', $md);
        }
    }


    function sendEmail($recipient_type, $recipient){
        if(!in_array(trim($recipient->official_email), ["",null])){
            $newMail = new RequestActivityMail(
                $recipient_type,
                $recipient,
                $this->staff_request,
                $this->request_category,
                $this->request_name,
                $this->action_name,
                $this->action,
                $this->is_next_level_notification
            );

            Mail::to($recipient->official_email)->send($newMail);

            //Reset Identifier for Next Level Notification
            $this->is_next_level_notification = false;

            //Wait for a short while Before sending another email
            sleep(1);
        }
    }

}
