<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffPerformance extends Model
{
    protected $guarded = [];

    public static $staff_performance_statuses = [
        '10' => 'Waiting For First Quoter Assessment',
        '20' => 'Waiting For Second Quoter Assessment',
        '30' => 'Waiting For Third Quoter Assessment',
        '40' => 'Waiting For Fourth Quoter Assessment',
        '50' => 'Assessment Completed',
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    /************************* my custom functions starts here *******************/
    public static function create_performance_entry_for_one_staff($staff_id){

        $year = date('Y');
        //check if entry have been created before
        $staff_performance = StaffPerformance::where('staff_id','=',$staff_id)
                                 ->where('year','=',$year)->get();

        //dd(count($staff_performance));

        if( count($staff_performance) > 0){
            //this means performance entry for this year have been created
        }

        else{
            //create staff performance entry
            StaffPerformance::create([
                'staff_id'=>$staff_id,
                'year'=>$year,
                'status'=>'10'
            ]);
        }

    }


    public static function create_performance_entries_for_all_staff(){

        $all_staff = Staff::get_valid_staff_list();

        if($all_staff){
            foreach($all_staff as $staff){
                self::create_performance_entry_for_one_staff($staff->id);
            }
        }

    }


}
