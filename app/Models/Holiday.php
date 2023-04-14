<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $guarded = [];


    public static function get_all_holidays_in_a_year($year){

        $holidays = Holiday::where('holiday_year','=',$year)->get();

        $holidays_array = [];
        if( count($holidays) > 0){

            foreach ($holidays as $holiday){
                $holidays_date = date('d-m-Y',strtotime($holiday->holiday_date));
                $holidays_array[$holidays_date] = $holiday->name;
            }

        }

        return [
            'arrays'  => $holidays_array,
            'objects' => $holidays,
        ];

    }
}
