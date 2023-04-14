<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;
use DatePeriod;

class AdvancePaymentRequest extends Model
{
    protected $guarded = [];

    public static $statuses = [
        '0'  => 'Returned For Correction',
        '10' => 'Waiting For Supervisor Approval',
        '20' => 'Waiting For Accountant Approval',
        '30' => 'Waiting For Finance Director Approval',
        '40' => 'Waiting For Managing Director Approval',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];

    public static $statusesApprovalUserRoleMapping = [
        '10' => '5',
        '20' => '4',
        '30' => '9',
        '40' => '2',
    ];


    public static function checkIfCurrentUserIsAllowedToApproveRequest($requestStatus, $userRole, $userStaffId, $responsibleSpvId): bool
    {
        $isAllowed = ( $userRole == 1 && $requestStatus <= 99 ); //Super Administrator
        $isAllowed = $isAllowed || ( $userRole == 2 && $requestStatus == 40 ); //MD
        $isAllowed = $isAllowed || ( $userRole == 9 && $requestStatus == 30 ); //FD
        $isAllowed = $isAllowed || ( $userRole == 4 && $requestStatus == 20 ); //ACC
        $isAllowed = $isAllowed || ( $userRole == 5 && $requestStatus == 10 ); //SPV
        $isAllowed = $isAllowed || ( $userStaffId == $responsibleSpvId &&  $requestStatus == 10 );

        return $isAllowed;
    }

    public static $statusesAllowedForEditing = ['0', '10'];

    public static $statusesAllowedForDeleting = ['0', '10', '99'];

    public static $statusesAllowedForDeletingForOtherStaff = ['0', '10', '99'];

    public static $rolesAllowedDeletingForOtherStaff = ["1","3"]; //Super Admin && HR

    public static $approvedStatus = 50;

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function approvals(){
        return $this->hasMany(RequestApproval::class);

    }

    public function rejection(){
        return $this->hasOne(RequestRejection::class);

    }

    public function returns(){
        return $this->hasMany(RequestReturn::class);

    }

    public function project(){
        return $this->belongsTo(Project::class);

    }

    public static function get_staff_advance_payment_request_by_date($staff_id, $requested_date){

        $advance_payment_request = AdvancePaymentRequest::where('staff_id', '=', $staff_id)
            ->where('requested_date','=',$requested_date)
            ->where('status','<>','99')->first(); //99 is for rejected advance_payment requests

        return $advance_payment_request;

    }


    public function get_staff_requests_by_status($staff_id,$year,$status){

        $advance_payment_info = ['advance_payment_requests' => [], 'total_days'=> 0];

        $advance_payment_requests = AdvancePaymentRequest::where('staff_id', '=', $staff_id)
            ->where('year','=',$year)
            ->where('status','=',$status)->get();

        $advance_payment_info['advance_payment_requests'] = $advance_payment_requests;

        return $advance_payment_info;

    }


    public function get_summary_for_staff($staff_id, $year = ''){

        $year =  $year == '' ? $year = date('Y') : $year;

        $advance_payment_requests_returned_for_correction   = $this->get_staff_requests_by_status($staff_id,$year,'0');
        $advance_payment_requests_waiting_for_spv_approval  = $this->get_staff_requests_by_status($staff_id,$year,'10');
        $advance_payment_requests_waiting_for_acc_approval  = $this->get_staff_requests_by_status($staff_id,$year,'20');
        $advance_payment_requests_waiting_for_fd_approval   = $this->get_staff_requests_by_status($staff_id,$year,'30');
        $advance_payment_requests_waiting_for_md_approval   = $this->get_staff_requests_by_status($staff_id,$year,'40');
        $advance_payment_requests_approved                  = $this->get_staff_requests_by_status($staff_id,$year,'50');
        $advance_payment_requests_rejected                  = $this->get_staff_requests_by_status($staff_id,$year,'99');


        $summary = [
            'returned-for-correction' => $advance_payment_requests_returned_for_correction,
            'waiting-spv-approval' => $advance_payment_requests_waiting_for_spv_approval,
            'waiting-acc-approval' => $advance_payment_requests_waiting_for_acc_approval,
            'waiting-fd-approval'  => $advance_payment_requests_waiting_for_fd_approval,
            'waiting-md-approval'  => $advance_payment_requests_waiting_for_md_approval,
            'approved'             => $advance_payment_requests_approved,
            'rejected'             => $advance_payment_requests_rejected,
        ];

        return $summary;

    }


    public static function countRequests(){
        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;
        if($user->category == "staff"){
            $employee_id = $user->staff->id;
        }

        //for my requests
        $returnedForCorrection = AdvancePaymentRequest::where('status', '=',  '0')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForSPVApproval = AdvancePaymentRequest::where('status', '=', '10')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForACCApproval = AdvancePaymentRequest::where('status', '=', '20')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForFDApproval  = AdvancePaymentRequest::where('status', '=', '30')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForMDApproval  = AdvancePaymentRequest::where('status', '=', '40')->where('staff_id','=',$employee_id)->get()->count();
        $approved              = AdvancePaymentRequest::where('status', '=', '50')->where('staff_id','=',$employee_id)->get()->count();
        $rejected              = AdvancePaymentRequest::where('status', '=', '99')->where('staff_id','=',$employee_id)->get()->count();

        //for approvals
        $returnedForCorrection2 = 0;
        $waitingForSPVApproval2 = 0;
        $waitingForACCApproval2 = 0;
        $waitingForFDApproval2  = 0;
        $waitingForMDApproval2  = 0;
        $approved2 = 0;
        $rejected2 = 0;


        if( in_array($current_user_role, [1,2,3,4,9]) ){ //for Super Administrator or MD or HRM
            $returnedForCorrection2 = AdvancePaymentRequest::where('status', '=', '0')->get()->count();
            $waitingForSPVApproval2 = AdvancePaymentRequest::where('status', '=', '10')->get()->count();
            $waitingForACCApproval2 = AdvancePaymentRequest::where('status', '=', '20')->get()->count();
            $waitingForFDApproval2  = AdvancePaymentRequest::where('status', '=', '30')->get()->count();
            $waitingForMDApproval2  = AdvancePaymentRequest::where('status', '=', '40')->get()->count();
            $approved2              = AdvancePaymentRequest::where('status', '=', '50')->get()->count();
            $rejected2              = AdvancePaymentRequest::where('status', '=', '99')->get()->count();
        }

        if($current_user_role == 5){ // for SPV
            $returnedForCorrection2 = AdvancePaymentRequest::where('status', '=', '0')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForSPVApproval2 = AdvancePaymentRequest::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForACCApproval2 = AdvancePaymentRequest::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForFDApproval2  = AdvancePaymentRequest::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2  = AdvancePaymentRequest::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2              = AdvancePaymentRequest::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2              = AdvancePaymentRequest::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
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
