<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePlan extends Model
{
    protected $guarded = [];


    public static $leave_plan_statuses = [
        '10' => 'Draft',
        '20' => 'Waiting For Supervisor Approval',
        '30' => 'Waiting For HRM Approval',
        '40' => 'Waiting For MD Approval',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];


    public static $months = [
        '1'  => 'January',
        '2'  => 'February',
        '3'  => 'March',
        '4'  => 'April',
        '5'  => 'May',
        '6'  => 'June',
        '7'  => 'July',
        '8'  => 'August',
        '9'  => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December'
    ];


    public function staff(){
        return $this->belongsTo(Staff::class);
    }


    public function lines(){
        return $this->hasMany(LeavePlanLine::class);
    }


    public function approvals(){
        return $this->hasMany(LeavePlanApproval::class);

    }


    public function rejection(){
        return $this->hasOne(LeavePlanReject::class);

    }


    public function returns(){
        return $this->hasMany(LeavePlanReturn::class);

    }


    public static function check_if_requested_leave_is_planned($leave){

        $planned_leave = null;

        $leave_plan = LeavePlan::where('staff_id','=',$leave->staff->id)
                                ->where('year','=',$leave->year)
                                ->where('status','=','50')->first();

        if(isset($leave_plan->id)){
            $lines = $leave_plan->lines;

            if( count($lines) > 0 ){
                foreach ($lines as $line){
                    if( $leave->type == $line->type_of_leave &&
                        $leave->starting_date == $line->starting_date &&
                        $leave->ending_date == $line->ending_date){

                        $planned_leave = $line;

                    }
                }
            }
        }

        return $planned_leave;
    }


    public function get_staff_leave_plan($staff_id,$leave_type,$year){

        $plan_info = ['plan' => new LeavePlan(),'plan_lines' => [], 'total_days'=> 0];

        $leave_plan = LeavePlan::where('staff_id', '=', $staff_id)->where('year','=',$year)->first();


        if(isset($leave_plan->id)){

            $plan_info['plan'] = $leave_plan;

            $plan_lines = $leave_plan->lines;

            if( count($plan_lines) > 0 ){

                $plan_info['plan_lines'] = $plan_lines;

                foreach ($plan_lines as $line){

                    if($line->type_of_leave == $leave_type){
                        $plan_info['total_days'] += MyFunctions::calculate_no_of_days_btn_dates($line->starting_date, $line->ending_date);
                    }
                }

            }

        }

        return $plan_info;

    }


    public function get_leave_plan_summary_for_staff($staff_id,$year = ''){

        $year =  $year == '' ? $year = date('Y') : $year;

        $leave_entitlement = LeaveEntitlement::get_leave_entitlements_by_year($staff_id, $year)['arrays'];

        $summary = [];

        if( count($leave_entitlement) > 0 ){

            //annual leave
            $annual_leave_plan              = $this->get_staff_leave_plan($staff_id, 'annual_leave', $year);
            $annual_leave_days_taken        = $annual_leave_plan['total_days'];
            $annual_leave_days_left         = $leave_entitlement['annual_leave'] - $annual_leave_days_taken;

            //Sick leave
            $sick_leave_plan                = $this->get_staff_leave_plan($staff_id, 'sick_leave', $year);
            $sick_leave_days_taken          = $sick_leave_plan['total_days'];
            $sick_leave_days_left           = $leave_entitlement['sick_leave'] - $sick_leave_days_taken;

            //Maternity leave
            $maternity_leave_plan           = $this->get_staff_leave_plan($staff_id, 'maternity_leave', $year);
            $maternity_leave_days_taken     = $maternity_leave_plan['total_days'];
            $maternity_leave_days_left      = $leave_entitlement['maternity_leave'] - $maternity_leave_days_taken;

            //Paternity leave
            $paternity_leave_plan           = $this->get_staff_leave_plan($staff_id, 'paternity_leave', $year);
            $paternity_leave_days_taken     = $paternity_leave_plan['total_days'];
            $paternity_leave_days_left      = $leave_entitlement['paternity_leave'] - $paternity_leave_days_taken;

            //Compassionate Leave
            $compassionate_leave_plan       = $this->get_staff_leave_plan($staff_id, 'compassionate_leave', $year);
            $compassionate_leave_days_taken = $compassionate_leave_plan['total_days'];
            $compassionate_leave_days_left  = $leave_entitlement['compassionate_leave'] - $compassionate_leave_days_taken;

            //Other
            $other_leave_plan               = $this->get_staff_leave_plan($staff_id, 'other', $year);
            $other_leave_days_taken         = $other_leave_plan['total_days'];
            $other_leave_days_left          = $leave_entitlement['other'] - $other_leave_days_taken;



            $summary = [
                'annual_leave'=> [
                    'days-taken'     => $annual_leave_days_taken,
                    'days-left'      => $annual_leave_days_left,
                    'entitled-days'  => $leave_entitlement['annual_leave'],
                ],
                'sick_leave'=> [
                    'days-taken'     => $sick_leave_days_taken,
                    'days-left'      => $sick_leave_days_left,
                    'entitled-days'  => $leave_entitlement['sick_leave'],
                ],
                'maternity_leave'=> [
                    'days-taken'     => $maternity_leave_days_taken,
                    'days-left'      => $maternity_leave_days_left,
                    'entitled-days'  => $leave_entitlement['maternity_leave'],
                ],
                'paternity_leave'=> [
                    'days-taken'     => $paternity_leave_days_taken,
                    'days-left'      => $paternity_leave_days_left,
                    'entitled-days'  => $leave_entitlement['paternity_leave'],
                ],
                'compassionate_leave'=> [
                    'days-taken'     => $compassionate_leave_days_taken,
                    'days-left'      => $compassionate_leave_days_left,
                    'entitled-days'  => $leave_entitlement['compassionate_leave'],
                ],
                'other'=> [
                    'days-taken'     => $other_leave_days_taken,
                    'days-left'      => $other_leave_days_left,
                    'entitled-days'  => $leave_entitlement['other'],
                ],


            ];

        }

        return $summary;

    }


    public static function countLeavePlans(){
        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;
        if($user->category == "staff"){
            $employee_id = $user->staff->id;
        }

        //for my requests
        $returnedForCorrection  = LeavePlan::where('status', '=',  '0')->where('staff_id','=',$employee_id)->get()->count();
        $drafts                 = LeavePlan::where('status', '=', '10')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForSPVApproval  = LeavePlan::where('status', '=', '20')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForHRMApproval  = LeavePlan::where('status', '=', '30')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForMDApproval   = LeavePlan::where('status', '=', '40')->where('staff_id','=',$employee_id)->get()->count();
        $approved               = LeavePlan::where('status', '=', '50')->where('staff_id','=',$employee_id)->get()->count();
        $rejected               = LeavePlan::where('status', '=', '99')->where('staff_id','=',$employee_id)->get()->count();

        //for approvals
        $returnedForCorrection2 = 0;
        $drafts2                = 0;
        $waitingForSPVApproval2 = 0;
        $waitingForHRMApproval2 = 0;
        $waitingForMDApproval2  = 0;
        $approved2 = 0;
        $rejected2 = 0;

        if( in_array($current_user_role,[1,2,3]) ){ //for Super Administrator or MD or HRM
            $returnedForCorrection2 = LeavePlan::where('status', '=', '0')->get()->count();
            $drafts2                = LeavePlan::where('status', '=', '10')->get()->count();
            $waitingForSPVApproval2 = LeavePlan::where('status', '=', '20')->get()->count();
            $waitingForHRMApproval2 = LeavePlan::where('status', '=', '30')->get()->count();
            $waitingForMDApproval2  = LeavePlan::where('status', '=', '40')->get()->count();
            $approved2              = LeavePlan::where('status', '=', '50')->get()->count();
            $rejected2              = LeavePlan::where('status', '=', '99')->get()->count();
        }

        if( in_array($current_user_role,[5,9]) ){ // for SPV
            $returnedForCorrection2 = LeavePlan::where('status', '=',  '0')->where('responsible_spv','=',$employee_id)->get()->count();
            $drafts2                = LeavePlan::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForSPVApproval2 = LeavePlan::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForHRMApproval2 = LeavePlan::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2  = LeavePlan::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2              = LeavePlan::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2              = LeavePlan::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
        }

        $plans = [
            'returnedForCorrection' => $returnedForCorrection,
            'drafts'                => $drafts,
            'waitingForSPVApproval' => $waitingForSPVApproval,
            'waitingForHRMApproval' => $waitingForHRMApproval,
            'waitingForMDApproval'  => $waitingForMDApproval,
            'approved'              => $approved,
            'rejected'              => $rejected,
            'returnedForCorrection2'=> $returnedForCorrection2,
            'drafts2'               => $drafts2,
            'waitingForSPVApproval2'=> $waitingForSPVApproval2,
            'waitingForHRMApproval2'=> $waitingForHRMApproval2,
            'waitingForMDApproval2' => $waitingForMDApproval2,
            'approved2'             => $approved2,
            'rejected2'             => $rejected2,
        ];

        return $plans;
    }

}
