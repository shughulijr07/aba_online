<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    protected $guarded = [];


    public function  leaves(){
        return $this->hasMany(Leave::class,'employee_id');
    }

    public function leave_entitlements(){
        return $this->hasMany(LeaveEntitlement::class);
    }

    public function leave_plan(){
        return $this->hasMany(LeavePlan::class);
    }

    public function  time_sheets(){
        return $this->hasMany(TimeSheet::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function jobTitle(){
        return $this->belongsTo(StaffJobTitle::class);
    }

    public function supervisor(){
        return $this->hasOne(Supervisor::class);
    }

    public function staff_biographical_data_sheet(){
        return $this->hasOne(StaffBiographicalDataSheet::class);
    }

    public function time_sheet_late_submissions(){
        return $this->hasMany(TimeSheetLateSubmission::class);
    }


    public function staff_performances(){
        return $this->hasMany(StaffPerformance::class);
    }


    public function dependents(){
        return $this->hasMany(StaffDependent::class);
    }

    public function emergency_contacts(){
        return $this->hasMany(StaffEmergencyContact::class);
    }

    public function status_changes(){
        return $this->hasMany(StaffStatusChange::class);
    }




    /************************* my custom functions starts here *******************/

    /********
     * $supervisors_mode =1 ; gets dedicated supervisor
     * $supervisors_mode =2 ; gets list of supervisors from database
     *******/
    public static function get_supervisors($supervisors_mode = '2'){

        $supervisors = [];

        if( $supervisors_mode == '1'){ //supervisor modes are found GeneralSettings Class |  '1' => 'Dedicated Supervisors'

            $supervisor_id = auth()->user()->staff->supervisor_id;

            if($supervisor_id>0){
                $supervisors = Staff::select('staff.id','first_name','last_name')
                    ->where('staff.id', $supervisor_id)
                    ->get();

            }else{ // if no supervisor have been assigned to this employee yet, just change mode of supervisors
                $supervisors_mode = '2';
            }

        }

        if( $supervisors_mode == '2'){ //supervisor modes are found GeneralSettings Class | '2' => 'Selection From List'
            //get supervisors from supervisors table

            $supervisors = Staff::select('staff.id','first_name','last_name')
                ->join('supervisors', 'supervisors.staff_id', '=', 'staff.id')
                ->get();

        }

        //dump($supervisors_mode);

        return $supervisors;

    }

    public static function get_valid_staff_list(){
        $staff_list = [];

        $all_staff = Staff::all();


        if ( auth()->user()->role_id == 1){

            $staff_list = $all_staff;

        }else{

            foreach ($all_staff as $staff){

                if( isset($staff->user->role_id) ){
                    $staff_role = $staff->user->role_id;
                    if( !in_array($staff_role, [1,8]) ){
                        $staff_list[] = $staff;
                    }
                }
            }

        }

        return $staff_list;

    }

       public static function new_get_valid_staff_list($supervisor_id){
        $staff_list = [];

        $all_staff = Staff::where('supervisor_id', $supervisor_id)->get();


        if ( auth()->user()->role_id == 1){

            $staff_list = $all_staff;

        }else{

            foreach ($all_staff as $staff){

                if( isset($staff->user->role_id) ){
                    $staff_role = $staff->user->role_id;
                    if( !in_array($staff_role, [1,8]) ){
                        $staff_list[] = $staff;
                    }
                }
            }
        }

        return $staff_list;

    }


    public static function get_valid_staff_list_by_status($staff_status){
        $staff_list = [];

        $all_staff = Staff::all();

        foreach ($all_staff as $staff){

            if( isset($staff->user->role_id) ){
                $staff_role = $staff->user->role_id;
                if( !in_array($staff_role, [1,8]) && $staff->staff_status == $staff_status ){
                    $staff_list[] = $staff;
                }
            }
        }
        return $staff_list;

    }

    public static function get_valid_staff_ids_list(){
        $staff_ids_list = [];

        $all_staff = Staff::all();


        if ( auth()->user()->role_id == 1){

            foreach ($all_staff as $staff){
                $staff_ids_list[] = $staff->id;
            }

        }else{

            foreach ($all_staff as $staff){

                if( isset($staff->user->role_id) ){
                    $staff_role = $staff->user->role_id;
                    if( $staff_role != 1 && $staff_role != 8 ){
                        $staff_ids_list[] = $staff->id;
                    }
                }
            }

        }

        return $staff_ids_list;

    }




    /********* This one is used to get the complete list of supervisors from database *****/
    public static function get_supervisors_list(){

        $supervisors = Staff::select('staff.id','first_name','last_name')
            ->join('supervisors', 'supervisors.staff_id', '=', 'staff.id')
            ->get();

        return $supervisors;

    }


}
