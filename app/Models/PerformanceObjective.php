<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceObjective extends Model
{
    protected $guarded = [];


    public static $performance_objective_statuses = [
        '0'  => 'Returned For Correction',
        '10' => 'Saved In Drafts',
        '20' => 'Waiting For Supervisor Approval',
        '30' => 'Waiting For HRM Approval',
        '40' => 'Waiting For MD Approval',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function lines(){
        return $this->hasMany(PerformanceObjectiveLine::class);
    }

    public function approvals(){
        return $this->hasMany(PerformanceObjectiveApproval::class);

    }

    public function rejection(){
        return $this->hasOne(PerformanceObjectiveReject::class);

    }

    public function returns(){
        return $this->hasMany(PerformanceObjectiveReturn::class);

    }

    public static function get_staff_objectives_by_year($staff_id, $year){

        $objectives = PerformanceObjective::where('staff_id', '=', $staff_id)
                    ->where('year','=',$year)
                    ->where('status','<>','99')->first();
                    //99 is for rejected travel requests

        return $objectives;

    }



    public static function countPerformanceObjectives(){
        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;
        if($user->category == "staff"){
            $employee_id = $user->staff->id;
        }

        //for my requests
        $returnedForCorrection = PerformanceObjective::where('status', '=',  '0')->where('staff_id','=',$employee_id)->get()->count();
        $drafts                = PerformanceObjective::where('status', '=', '10')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForSPVApproval = PerformanceObjective::where('status', '=', '20')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForHRMApproval = PerformanceObjective::where('status', '=', '30')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForMDApproval  = PerformanceObjective::where('status', '=', '40')->where('staff_id','=',$employee_id)->get()->count();
        $approved              = PerformanceObjective::where('status', '=', '50')->where('staff_id','=',$employee_id)->get()->count();
        $rejected              = PerformanceObjective::where('status', '=', '99')->where('staff_id','=',$employee_id)->get()->count();

        //for approvals
        $returnedForCorrection2 = 0;
        $drafts2                = 0;
        $waitingForSPVApproval2 = 0;
        $waitingForHRMApproval2 = 0;
        $waitingForMDApproval2  = 0;
        $approved2 = 0;
        $rejected2 = 0;

        if( in_array($current_user_role,[1,2,3]) ){ //for Super Administrator or MD or HRM
            $returnedForCorrection2 = PerformanceObjective::where('status', '=', '0')->get()->count();
            $drafts2                = PerformanceObjective::where('status', '=', '10')->get()->count();
            $waitingForSPVApproval2 = PerformanceObjective::where('status', '=', '20')->get()->count();
            $waitingForHRMApproval2 = PerformanceObjective::where('status', '=', '30')->get()->count();
            $waitingForMDApproval2  = PerformanceObjective::where('status', '=', '40')->get()->count();
            $approved2              = PerformanceObjective::where('status', '=', '50')->get()->count();
            $rejected2              = PerformanceObjective::where('status', '=', '99')->get()->count();
        }

        if( in_array($current_user_role,[5,9]) ){ // for SPV
            $returnedForCorrection2 = PerformanceObjective::where('status', '=', '0')->where('responsible_spv','=',$employee_id)->get()->count();
            $drafts2                = PerformanceObjective::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForSPVApproval2 = PerformanceObjective::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForHRMApproval2 = PerformanceObjective::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2  = PerformanceObjective::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2              = PerformanceObjective::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2              = PerformanceObjective::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
        }

        $applications = [
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

        return $applications;
    }

}
