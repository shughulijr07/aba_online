<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEntitlement extends Model
{
    protected $guarded = [];

    public static $leave_entitlement = [
        'annual-leave' => '28',
        'sick-leave' => '0',
        'maternity-leave' => '0',
        'paternity-leave' => '0',
        'compassionate-leave' => '0',
        'study-leave' => '0',
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function lines(){
        return $this->hasMany(LeaveEntitlementLine::class);
    }

    public function create_leave_entitlement_for_one_staff($staff_id,$year = ''){

        $year = $year == '' ? date('Y') : $year;

        $entitlement = LeaveEntitlement::where('staff_id','=',$staff_id)->where('year', '=', $year)->first();

        if(!isset($entitlement->id)){
            //do leave entitlement

            $entitlement = new LeaveEntitlement();
            $entitlement->staff_id = $staff_id;
            $entitlement->year = $year;
            $entitlement->save();

            //create entitlement lines
            $leave_types = LeaveType::where('status','=','Active')->get();
            foreach ($leave_types as $leave_type){

                $line = new LeaveEntitlementLine();
                $line->leave_entitlement_id = $entitlement->id;
                $line->type_of_leave = $leave_type->key;
                $line->number_of_days = $leave_type->days;
                $line->save();

            }

        }

        //check first if entitlement have been done

    }


    public function create_leave_entitlement_for_all_staff($year=''){

        $year = $year == '' ? date('Y') : $year;

        $all_staff = Staff::get_valid_staff_list();

        foreach ($all_staff as $staff){
            $this->create_leave_entitlement_for_one_staff($staff->id,$year);
        }

    }


    public static function get_leave_entitlements_by_year($staff_id,$year){


        $entitlements = [ 'objects' => '', 'arrays' => [] ];

        $leave_entitlement = LeaveEntitlement::where('staff_id', '=', $staff_id)->where('year','=',$year)->first();


        if( isset($leave_entitlement)){
            $lines = $leave_entitlement->lines;

            $lines_array = [];
            foreach ($lines as $line){
                $lines_array[$line->type_of_leave] = $line->number_of_days;
            }

            $entitlements['objects'] = $lines;
            $entitlements['arrays'] = $lines_array;
        }
        return $entitlements;
    }


    public function make_carry_over_for_one_staff($staff_id){

        $year =  date('Y');

        //check if staff was entitled in previous year
        $entitlement = LeaveEntitlement::where('staff_id', '=', $staff_id)
                                       ->where('year', '=', $year-1)->first();

        if( isset($entitlement->id) ){ // then continue

            //check if carry over have been done for this year
            $entitlement_lines = self::get_leave_entitlements_by_year($staff_id,$year)['objects'];
            $annual_leave_line = null;

            foreach ($entitlement_lines as $line){
               if($line->type_of_leave == 'annual_leave' ){$annual_leave_line = $line;}
            }

            if( $annual_leave_line != null ){

                $annual_leave_carry_over = $annual_leave_line->carry_over;

                //if annual leave carry over have not been done then do it
                if($annual_leave_carry_over == null){

                    //perform carry over

                    //get leave summary for Staff in the previous year
                    $leave_summary = (new Leave())->get_leave_summary_for_staff($staff_id,$year-1);

                    //get days left in annual leave
                    $annual_leave_days_left = $leave_summary['annual_leave']['days-left'];
                    $current_entitled_days = $annual_leave_line->number_of_days;

                    $annual_leave_line->number_of_days = $current_entitled_days + $annual_leave_days_left;
                    $annual_leave_line->save();

                    //record carry over
                    $carry_over = new LeaveEntitlementCarry();
                    $carry_over->leave_entitlement_line_id = $annual_leave_line->id;
                    $carry_over->from_year = $year-1;
                    $carry_over->no_days = $annual_leave_days_left;
                    $carry_over->done_by = auth()->user()->staff->id;
                    $carry_over->reason = 'Created Automatically';
                    $carry_over->save();


                    //dd($carry_over);

                }

            }
        }



    }


    public function make_carry_over_for_all_staff(){

        $all_staff = Staff::get_valid_staff_list();

        foreach ($all_staff as $staff){
            $this->make_carry_over_for_one_staff($staff->id);
        }


    }


}
