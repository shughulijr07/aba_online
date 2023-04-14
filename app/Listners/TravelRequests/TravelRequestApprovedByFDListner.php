<?php

namespace App\Listners\TravelRequests;

use App\Events\TravelRequestApprovedByFDEvent;
use App\Mail\TravelRequestApprovedByAccountant;
use App\Mail\TravelRequestApprovedByFinanceDirector;
use App\Mail\TravelRequestApprovedByManagingDirector;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class TravelRequestApprovedByFDListner implements ShouldQueue
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
     * @param  TravelRequestApprovedByFDEvent  $event
     * @return void
     */
    public function handle(TravelRequestApprovedByFDEvent $event)
    {
        $travel_request = $event->travel_request;


        //send email to employee
        $staff = $travel_request->staff;
        $employee_official_email = $staff->official_email;

        $recipient_type = 'staff';
        $recipient = $staff;
        Mail::to($employee_official_email)->send(new TravelRequestApprovedByFinanceDirector($recipient_type,$recipient,$travel_request));
        sleep(10);




        //send email to Supervisor
        $supervisor = Staff::find($travel_request->responsible_spv);
        $spv_official_email = $supervisor->official_email;

        $recipient_type = 'spv';
        $recipient = $supervisor;
        Mail::to($spv_official_email)->send(new TravelRequestApprovedByFinanceDirector($recipient_type,$recipient,$travel_request));
        sleep(10);



        //send email to Accountants
        //get Accountants
        $users = User::where('role_id','=','4')->get(); // 4 is role id for Accountants

        if( count($users)>0 ){

            foreach ($users as $user){
                $acc = $user->staff;
                $acc_official_email = $acc->official_email;

                $recipient_type = 'acc';
                $recipient = $acc;
                Mail::to($acc_official_email)->send(new TravelRequestApprovedByFinanceDirector($recipient_type,$recipient,$travel_request));
                sleep(10);

            }

        }



        //send email to FD
        $user = User::where('role_id','=','9')->get()->first(); // 9 is role id for Finance Director

        $fd = $user->staff;
        $fd_official_email = $fd->official_email;

        $recipient_type = 'fd';
        $recipient = $fd;
        Mail::to($fd_official_email)->send(new TravelRequestApprovedByFinanceDirector($recipient_type,$recipient,$travel_request));
        sleep(10);



        //send email to MD
        $user = User::where('role_id','=','2')->get()->first(); // 2 is role id for FinanceDirectors

        $md = $user->staff;
        $md_official_email = $md->official_email;

        $recipient_type = 'md';
        $recipient = $md;
        Mail::to($md_official_email)->send(new TravelRequestApprovedByFinanceDirector($recipient_type,$recipient,$travel_request));
        sleep(10);

    }

}
