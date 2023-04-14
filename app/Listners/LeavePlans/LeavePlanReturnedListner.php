<?php

namespace App\Listners\LeavePlans;

use App\Events\LeavePlanReturnedEvent;
use App\Mail\LeavePlanReturnedForCorrection;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class LeavePlanReturnedListner implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LeavePlanReturnedEvent  $event
     * @return void
     */
    public function handle(LeavePlanReturnedEvent $event)
    {
        $leave_plan = $event->leave_plan;
        $return_level = $event->return_level;


        //send email to employee
        $staff = $leave_plan->staff;
        $employee_official_email = $staff->official_email;

        $recipient_type = 'staff';
        $recipient = $staff;
        Mail::to($employee_official_email)->send(new LeavePlanReturnedForCorrection($recipient_type,$recipient,$leave_plan,$return_level));
        sleep(10);




        //send email to Supervisor
        $supervisor = Staff::find($leave_plan->responsible_spv);
        $spv_official_email = $supervisor->official_email;

        $recipient_type = 'spv';
        $recipient = $supervisor;
        Mail::to($spv_official_email)->send(new LeavePlanReturnedForCorrection($recipient_type,$recipient,$leave_plan,$return_level));
        sleep(10);




        //send email to HRM
        //get HRMs
        $users = User::where('role_id','=','3')->get(); // 3 is role id for HRMs

        if( count($users)>0 ){
            foreach ($users as $user){
                $hrm = $user->staff;
                $hrm_official_email = $hrm->official_email;

                $recipient_type = 'hrm';
                $recipient = $hrm;
                Mail::to($hrm_official_email)->send(new LeavePlanReturnedForCorrection($recipient_type,$recipient,$leave_plan,$return_level));
                sleep(10);
            }
        }

        /*
        //send email to MD
        $user = User::where('role_id','=','2')->get()->first(); // 3 is role id for HRMs

        $md = $user->staff;
        $md_official_email = $md->official_email;

        $recipient_type = 'md';
        $recipient = $md;
        Mail::to($md_official_email)->send(new LeavePlanReturnedForCorrection($recipient_type,$recipient,$leave_plan,$return_level));
        sleep(10);

        */
    }
}
