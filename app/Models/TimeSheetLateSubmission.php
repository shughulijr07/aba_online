<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSheetLateSubmission extends Model
{
    protected $guarded = [];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function time_sheet(){
        return $this->belongsTo(TimeSheet::class);
    }

    public static function lock_late_time_sheet_submissions(){


        //get submission deadline from settings
        $system_settings = GeneralSetting::find(1);
        $submission_deadline = $system_settings->time_sheet_submission_deadline;

        $current_day = date('j');
        $current_month = date('n');
        $current_year = date('Y');
        $closing_year = $current_year;
        $closing_month = $current_month-1;
        $current_month == 1 ? $closing_year = $current_year-1 : $closing_year = $current_year;
        $current_month == 1 ? $closing_month = 12 : $closing_month = $current_month - 1;

        //check if closing have been done for the previous month
        $closing = TimeSheetClosing::where('year','=',$closing_year)->where('month','=',$closing_month)->get();

        //if closing is not done then close time sheet submission if deadline have passed
        if( count($closing) == 0 ){

            //if deadline have passed mark late submissions and close timesheet month
            if( $current_day > $submission_deadline){

                //get all staff
                $staffs = Staff::get_valid_staff_list();
                $late_submitters = [];
                //check each staff if has already submitted time sheet against deadline
                foreach ($staffs as $staff){

                    $submitted_time_sheets = TimeSheet::where('staff_id','=',$staff->id)
                        ->where('year','=',$closing_year)
                        ->where('month','=',$closing_month)
                        ->latest()->get();

                    if( count($submitted_time_sheets) == 0 ){
                        //have not created time sheets at all
                        $late_submitters[] = $staff->id;
                    }
                    else{
                        $submitted_time_sheet = $submitted_time_sheets[0];

                        if( !in_array($submitted_time_sheet->status,[20,30,40,50])){
                            //created time sheets but have not submitted them
                            $late_submitters[] = $staff->id;
                        }
                    }

                }


                //add these staff to late time sheet submission table
                foreach ($late_submitters as $id){

                    TimeSheetLateSubmission::create([
                        'staff_id' => $id,
                        'year' => $closing_year,
                        'month' => $closing_month,
                        'status' => 'locked',
                    ]);

                }


                //close time sheet month
                TimeSheetClosing::create([
                    'year' => $closing_year,
                    'month' => $closing_month,
                    'closed_on' => $submission_deadline,
                ]);

            }


        }


    }

    public static function check_if_is_allowed_to_submit($staff_id,$year,$month){

        $feedback = [ 'id'=>'', 'status'=>'allowed' ];

        $late_submission = TimeSheetLateSubmission::where('staff_id','=',$staff_id)
            ->where('year','=',$year)
            ->where('month','=',$month)
            ->latest()->first();

        if( isset($late_submission->id)){ //found in late submission table

            //check if have been followed procedures to unlock submission & have been unlocked by HR-Manager
            if( !in_array( $late_submission->reason,[null,'']) &&  $late_submission->status == 'unlocked' && !in_array( $late_submission->unlocked_by,[null,''])){
                $feedback = [ 'id'=>'', 'status'=>'allowed' ];
            }else{
                $feedback = [ 'id'=>$late_submission->id, 'status'=>'not-allowed' ];
            }

        }else{ // not found in late submission table
            $feedback = [ 'id'=>'', 'status'=>'allowed' ];
        }

        return $feedback;

    }

}
