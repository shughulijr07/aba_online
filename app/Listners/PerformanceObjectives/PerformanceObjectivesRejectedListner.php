<?php

namespace App\Listners\PerformanceObjectives;

use App\Events\PerformanceObjectivesRejectedEvent;
use App\Mail\PerformanceObjectivesRejected;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class PerformanceObjectivesRejectedListner implements ShouldQueue
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
     * @param  PerformanceObjectivesRejectedEvent  $event
     * @return void
     */
    public function handle(PerformanceObjectivesRejectedEvent $event)
    {
        $performance_objective = $event->performance_objective;
        $reject_level = $event->reject_level;


        //send email to employee
        $staff = $performance_objective->staff;
        $employee_official_email = $staff->official_email;

        $recipient_type = 'staff';
        $recipient = $staff;
        Mail::to($employee_official_email)->send(new PerformanceObjectivesRejected($recipient_type,$recipient,$performance_objective,$reject_level));
        sleep(10);




        //send email to Supervisor
        $supervisor = Staff::find($performance_objective->responsible_spv);
        $spv_official_email = $supervisor->official_email;

        $recipient_type = 'spv';
        $recipient = $supervisor;
        Mail::to($spv_official_email)->send(new PerformanceObjectivesRejected($recipient_type,$recipient,$performance_objective,$reject_level));
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
                Mail::to($hrm_official_email)->send(new PerformanceObjectivesRejected($recipient_type,$recipient,$performance_objective,$reject_level));
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
        Mail::to($md_official_email)->send(new PerformanceObjectivesRejected($recipient_type,$recipient,$performance_objective,$reject_level));
        sleep(10);

        */

    }
}
