<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberSeries extends Model
{
    protected $guarded = [];

    public function  numbered_item(){
        return $this->belongsTo(NumberedItem::class);
    }

    public static function get_next_number_from_series($item_id){

        $number_series = NumberSeries::where('numbered_item_id','=',$item_id)->first();

        $no_series_id = $number_series['id'];
        $abbreviation = ucwords($number_series['abbreviation']);
        $separator = $number_series['separator'] == "blank" ? "" : $number_series['separator'];
        $include_year = $number_series['include_year'];
        $include_month = $number_series['include_month'];
        $year_in_use  = $number_series['year'];
        $month_in_use = $number_series['month'];
        $increment_by = $number_series['increment_by'];
        $last_no_used = $number_series['last_no_used'];
        $number_of_digits = $number_series['number_of_digits'];
        $reset_on = $number_series['reset_on'];

        //set next no
        $next_no = '';
        $formatted_next_no = '';
        if( $include_year == 'Yes' && $reset_on == 'Year' && $year_in_use != date('Y') ){ // if year have changed
            $next_no = 1;
            $formatted_next_no =  str_pad($next_no, $number_of_digits, '0', STR_PAD_LEFT);
        }
        else if( $include_year == 'Yes' && $include_month == 'Yes' && $reset_on == 'Month' &&  $month_in_use != date('m') ){
            $next_no = 1;
            $formatted_next_no =  str_pad($next_no, $number_of_digits, '0', STR_PAD_LEFT);
        }
        else{
            $next_no = $last_no_used + $increment_by;
            $formatted_next_no =  str_pad($next_no, $number_of_digits, '0', STR_PAD_LEFT);
        }

        //set next code
        $next_no_code = '';
        if ($include_year == 'Yes' && $include_month == 'Yes'){
            $next_no_code = $abbreviation.$separator.date('m'.$separator.'Y').$separator.$formatted_next_no;
        }
        elseif ($include_year == 'Yes' && $include_month == 'No'){
            $next_no_code = $abbreviation.$separator.date('Y').$separator.$formatted_next_no;
        }else{
            $next_no_code = $abbreviation.$separator.$formatted_next_no;
        }


        return ['no_series_id' =>$no_series_id,'no'=>$next_no, 'code'=>$next_no_code];

    }

    public static function save_last_number_used($itm_no_series){
        $item_no_series = NumberSeries::find($itm_no_series['no_series_id']);
        $item_no_series->last_no_used = $itm_no_series['no'];
        $item_no_series->last_no_used_code = $itm_no_series['code'];
        $item_no_series->year = date('Y');
        $item_no_series->month = date('m');
        $item_no_series->save();
    }

}
