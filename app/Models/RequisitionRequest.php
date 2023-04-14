<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;
use DatePeriod;

class RequisitionRequest extends Model
{
    protected $guarded = [];

    public static $travel_request_statuses = [
        '0'  => 'Returned For Correction',
        '10' => 'Waiting For Supervisor Approval',
        '20' => 'Waiting For Accountant Approval',
        '30' => 'Waiting For Finance Director Approval',
        '40' => 'Waiting For Managing Director Approval',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];


    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function lines(){
        return $this->hasMany(RequisitionRequestLine::class);
    }

    public function approvals(){
        return $this->hasMany(RequisitionRequestApproval::class);

    }

    public function rejection(){
        return $this->hasOne(RequisitionRequestReject::class);

    }

    public function returns(){
        return $this->hasMany(RequisitionRequestReturn::class);

    }

    public static function get_staff_travel_request_by_date($staff_id, $requested_date){

        $travel_request = RequisitionRequest::where('staff_id', '=', $staff_id)
            ->where('requested_date','=',$requested_date)
            ->where('status','<>','99')->first(); //99 is for rejected travel requests

        return $travel_request;

    }


    public function get_staff_travel_requests_by_status($staff_id,$year,$status){

        $travel_info = ['travel_requests' => [], 'total_days'=> 0];

        $travel_requests = RequisitionRequest::where('staff_id', '=', $staff_id)
            ->where('year','=',$year)
            ->where('status','=',$status)->get();

        $travel_info['travel_requests'] = $travel_requests;


        if( count($travel_requests) > 0 ){

            foreach ($travel_requests as $travel_request){
                $travel_info['total_days'] += $this->calculate_no_of_days_btn_dates($travel_request->departure_date, $travel_request->returning_date);
            }

        }

        return $travel_info;

    }


    public function calculate_no_of_days_btn_dates($starting_date,$ending_date){

        $starting_date = str_replace('/', '-', $starting_date);
        $ending_date = str_replace('/', '-', $ending_date);

        $starting_date = new DateTime($starting_date);
        $ending_date = new DateTime($ending_date);
        // otherwise the  end date is excluded (bug?)
        $ending_date->modify('+1 day');

        $interval = $ending_date->diff($starting_date);
        $number_of_days = $interval->days;

        //exclude holidays & weekends according to settings
        $system_settings = GeneralSetting::find(1);
        $include_holidays_in_leave = $system_settings->include_holidays_in_leave;
        $include_weekends_in_leave = $system_settings->include_weekends_in_leave;

        // create an iterate-able period of date (P1D equates to 1 day)
        $period = new DatePeriod($starting_date, new DateInterval('P1D'), $ending_date);

        $year1 =  $starting_date->format('Y');
        $year2 =  $ending_date->format('Y'); //we are recording the second year for leaves which will fall between years e.g 24-12-2019 to 15-01-2020
        $holidays1 = (Holiday::get_all_holidays_in_a_year($year1))['arrays'];
        $holidays2 = (Holiday::get_all_holidays_in_a_year($year2))['arrays'];
        $holiday_dates1 = array_keys($holidays1);
        $holiday_dates2 = array_keys($holidays2);

        /*
        foreach($period as $dt) {

            $curr = $dt->format('D');

            if( $include_weekends_in_leave == 2 && ($curr == 'Sat' || $curr == 'Sun') ){// subtract if Saturday or Sunday
                $number_of_days--;
            }
            elseif( $include_holidays_in_leave == 2 &&
                ( in_array($dt->format('d-m-Y'), $holiday_dates1) || in_array($dt->format('d-m-Y'), $holiday_dates2) )
            ){ //exclude holidays
                $number_of_days--;
            }

        }
        */


        return $number_of_days;

    }


    public function get_travel_summary_for_staff($staff_id,$year = ''){

        $year =  $year == '' ? $year = date('Y') : $year;

        $travel_requests_returned_for_correction   = $this->get_staff_travel_requests_by_status($staff_id,$year,'0');
        $travel_requests_waiting_for_spv_approval  = $this->get_staff_travel_requests_by_status($staff_id,$year,'10');
        $travel_requests_waiting_for_acc_approval  = $this->get_staff_travel_requests_by_status($staff_id,$year,'20');
        $travel_requests_waiting_for_fd_approval   = $this->get_staff_travel_requests_by_status($staff_id,$year,'30');
        $travel_requests_waiting_for_md_approval   = $this->get_staff_travel_requests_by_status($staff_id,$year,'40');
        $travel_requests_approved                  = $this->get_staff_travel_requests_by_status($staff_id,$year,'50');
        $travel_requests_rejected                  = $this->get_staff_travel_requests_by_status($staff_id,$year,'99');


        $summary = [
            'returned-for-correction' => $travel_requests_returned_for_correction,
            'waiting-spv-approval' => $travel_requests_waiting_for_spv_approval,
            'waiting-acc-approval' => $travel_requests_waiting_for_acc_approval,
            'waiting-fd-approval'  => $travel_requests_waiting_for_fd_approval,
            'waiting-md-approval'  => $travel_requests_waiting_for_md_approval,
            'approved'             => $travel_requests_approved,
            'rejected'             => $travel_requests_rejected,
        ];

        return $summary;

    }



    public static function countTravelRequests(){

        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;
        if($user->category == "staff"){
            $employee_id = $user->staff->id;
        }

        //for my requests
        $returnedForCorrection = RequisitionRequest::where('status', '=',  '0')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForSPVApproval = RequisitionRequest::where('status', '=', '10')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForACCApproval = RequisitionRequest::where('status', '=', '20')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForFDApproval  = RequisitionRequest::where('status', '=', '30')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForMDApproval  = RequisitionRequest::where('status', '=', '40')->where('staff_id','=',$employee_id)->get()->count();
        $approved              = RequisitionRequest::where('status', '=', '50')->where('staff_id','=',$employee_id)->get()->count();
        $rejected              = RequisitionRequest::where('status', '=', '99')->where('staff_id','=',$employee_id)->get()->count();

        //for approvals
        $returnedForCorrection2 = 0;
        $waitingForSPVApproval2 = 0;
        $waitingForACCApproval2 = 0;
        $waitingForFDApproval2  = 0;
        $waitingForMDApproval2  = 0;
        $approved2 = 0;
        $rejected2 = 0;

        if( in_array($current_user_role, [1,2,3,4,9]) ){ //for Super Administrator or MD or HRM
            $returnedForCorrection2 = RequisitionRequest::where('status', '=', '0')->get()->count();
            $waitingForSPVApproval2 = RequisitionRequest::where('status', '=', '10')->get()->count();
            $waitingForACCApproval2 = RequisitionRequest::where('status', '=', '20')->get()->count();
            $waitingForFDApproval2  = RequisitionRequest::where('status', '=', '30')->get()->count();
            $waitingForMDApproval2  = RequisitionRequest::where('status', '=', '40')->get()->count();
            $approved2              = RequisitionRequest::where('status', '=', '50')->get()->count();
            $rejected2              = RequisitionRequest::where('status', '=', '99')->get()->count();
        }

        if($current_user_role == 5){ // for SPV
            $returnedForCorrection2 = RequisitionRequest::where('status', '=', '0')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForSPVApproval2 = RequisitionRequest::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForACCApproval2 = RequisitionRequest::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForFDApproval2  = RequisitionRequest::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2  = RequisitionRequest::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2              = RequisitionRequest::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2              = RequisitionRequest::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
        }

        $requests = [
            'returnedForCorrection' => $returnedForCorrection,
            'waitingForSPVApproval' => $waitingForSPVApproval,
            'waitingForACCApproval' => $waitingForACCApproval,
            'waitingForFDApproval'  => $waitingForFDApproval,
            'waitingForMDApproval'  => $waitingForMDApproval,
            'approved'              => $approved,
            'rejected'              => $rejected,
            'returnedForCorrection2'=> $returnedForCorrection2,
            'waitingForSPVApproval2'=> $waitingForSPVApproval2,
            'waitingForACCApproval2'=> $waitingForACCApproval2,
            'waitingForFDApproval2' => $waitingForFDApproval2,
            'waitingForMDApproval2' => $waitingForMDApproval2,
            'approved2'             => $approved2,
            'rejected2'             => $rejected2,
        ];

        return $requests;
    }

}
