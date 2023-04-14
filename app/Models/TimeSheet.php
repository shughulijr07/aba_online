<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    protected $guarded = [];

    public static $time_sheet_types = [
        'daily-time-sheet' => 'Daily Time Sheet',
        'weekly-time-sheet' => 'Weekly Time Sheet',
        'monthly-time-sheet' => 'Monthly Time Sheet',
    ];

    public static $timesheet_statuses = [
        '0'  => 'Returned For Correction',
        '10' => 'Saved To Drafts',
        '20' => 'Waiting For Supervisor Approval',
        '30' => 'Waiting For HRM Approval',
        '40' => 'Waiting For MD Approval',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];

        const TIMESHEET_STATUS = [
        ['id'=>0, 'name'=>'Returned For Correction'],
        ['id'=>10, 'name'=>'Saved To Drafts'],
        ['id'=>20, 'name'=>'Waiting For Supervisor Approval'],
        ['id'=>30, 'name'=>'Waiting For HRM Approval'],
        ['id'=>40, 'name'=>'Waiting For MD Approval'],
        ['id'=>50, 'name'=>'Approved'],
        ['id'=>59, 'name'=>'Rejected'],
    ];

    public static function getStatus()
    {
        return collect(static::TIMESHEET_STATUS);//->firstWhere('id', $this->list_education_level)['edulevel'] ?? '';
    }

    public static function findStatus($id){
        $collection = collect(static::TIMESHEET_STATUS);
        return $collection->firstWhere('id', $id)['name'] ?? "";
    }


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

    public static $projects = [
        '1000-200-03-81' => 'T-MARC/PHE',
        '3000-100-05-10' => 'USESA',
        '4000-100-05-10' => 'USAID Tulonge Afya',
        '1010-100-05-05' => 'T-MARC Tz General',
        '6000-100-05-05' => 'Badilisha Tabia, tokomeza Malaria ',
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function lines(){
        return $this->hasMany(TimeSheetLine::class);
    }

    public function json_lines(){
        return $this->hasOne(JsonTimeSheetLine::class);
    }

    public function approvals(){
        return $this->hasMany(TimeSheetApproval::class);

    }

    public function rejection(){
        return $this->hasOne(TimeSheetReject::class);

    }

    public function returns(){
        return $this->hasMany(TimeSheetReturn::class);

    }

    public function late_submissions(){
        return $this->hasMany(TimeSheetLateSubmission::class);

    }

    public static function change_timesheet_supervisor($staff_id, $new_supervisor_id){

       //Get all timesheets which are not submitted for this employee

        $time_sheets = TimeSheet::where('staff_id', '=', $staff_id)
            ->where('status','<>','50')->get(); //99 is for rejected time sheets

       //change supervisor for each timesheet

       if(count($time_sheets) > 0){

           foreach ($time_sheets as $time_sheet){

               //dump($time_sheet->responsible_spv); dump($new_supervisor_id);
               $time_sheet->responsible_spv = $new_supervisor_id;
               //dump($time_sheet->responsible_spv);
               $time_sheet->save();
               //dd($time_sheet->responsible_spv);
           }

       }



    }

    public static function get_timesheet_by_date($staff_id,$year,$month){

        $time_sheet = TimeSheet::where('staff_id', '=', $staff_id)
            ->where('year','=',$year)
            ->where('month','=',$month)
            ->where('status','<>','99')->first(); //99 is for rejected time sheets

        return $time_sheet;

    }



    public static function countTimeSheets(){
        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;
        if($user->category == "staff" ){
            $employee_id = $user->staff->id;
        }

        //for my requests
        $returnedForCorrection = TimeSheet::where('status', '=',  '0')->where('staff_id','=',$employee_id)->get()->count();
        $drafts                = TimeSheet::where('status', '=', '10')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForSPVApproval = TimeSheet::where('status', '=', '20')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForHRMApproval = TimeSheet::where('status', '=', '30')->where('staff_id','=',$employee_id)->get()->count();
        $waitingForMDApproval  = TimeSheet::where('status', '=', '40')->where('staff_id','=',$employee_id)->get()->count();
        $approved              = TimeSheet::where('status', '=', '50')->where('staff_id','=',$employee_id)->get()->count();
        $rejected              = TimeSheet::where('status', '=', '99')->where('staff_id','=',$employee_id)->get()->count();

        //for approvals
        $returnedForCorrection2 = 0;
        $drafts2                = 0;
        $waitingForSPVApproval2 = 0;
        $waitingForHRMApproval2 = 0;
        $waitingForMDApproval2  = 0;
        $approved2 = 0;
        $rejected2 = 0;

        if( in_array($current_user_role,[1,2,3]) ){ //for Super Administrator or MD or HRM
            $returnedForCorrection2 = TimeSheet::where('status', '=', '0')->get()->count();
            $drafts2                = TimeSheet::where('status', '=', '10')->get()->count();
            $waitingForSPVApproval2 = TimeSheet::where('status', '=', '20')->get()->count();
            $waitingForHRMApproval2 = TimeSheet::where('status', '=', '30')->get()->count();
            $waitingForMDApproval2  = TimeSheet::where('status', '=', '40')->get()->count();
            $approved2              = TimeSheet::where('status', '=', '50')->get()->count();
            $rejected2              = TimeSheet::where('status', '=', '99')->get()->count();
        }

        if( in_array($current_user_role,[5,9]) ){ // for SPV
            $returnedForCorrection2 = TimeSheet::where('status', '=', '0')->where('responsible_spv','=',$employee_id)->get()->count();
            $drafts2                = TimeSheet::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForSPVApproval2 = TimeSheet::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForHRMApproval2 = TimeSheet::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2  = TimeSheet::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2              = TimeSheet::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2              = TimeSheet::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
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
