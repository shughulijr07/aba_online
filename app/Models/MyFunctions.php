<?php

namespace App\Models;

use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Database\Eloquent\Model;

class MyFunctions extends Model
{



    //convert date to format which is recognized by mysql
    public static function convert_date_to_mysql_format($origDate){

        $date = str_replace('/', '-', $origDate );
        $newDate = date("Y-m-d", strtotime($date));
        $newDate;

        return $newDate;

    }


    public static function calculate_no_of_days_btn_dates($starting_date,$ending_date){

        $starting_date = str_replace('/', '-', $starting_date);
        $ending_date = str_replace('/', '-', $ending_date);

        $starting_date = new DateTime($starting_date);
        $ending_date = new DateTime($ending_date);
        // otherwise the  end date is excluded (bug?)
        $ending_date->modify('+1 day');

        $interval = $ending_date->diff($starting_date);
        $number_of_days = $interval->days;

        //exclude holidays & weekends according to settings
        $system_settings = GeneralSetting::find(1);
        $include_holidays_in_leave = $system_settings->include_holidays_in_leave;
        $include_weekends_in_leave = $system_settings->include_weekends_in_leave;

        // create an iterate-able period of date (P1D equates to 1 day)
        $period = new DatePeriod($starting_date, new DateInterval('P1D'), $ending_date);

        $year1 =  $starting_date->format('Y');
        $year2 =  $ending_date->format('Y'); //we are recording the second year for leaves which will fall between years e.g 24-12-2019 to 15-01-2020
        $holidays1 = (Holiday::get_all_holidays_in_a_year($year1))['arrays'];
        $holidays2 = (Holiday::get_all_holidays_in_a_year($year2))['arrays'];
        $holiday_dates1 = array_keys($holidays1);
        $holiday_dates2 = array_keys($holidays2);

        foreach($period as $dt) {

            $curr = $dt->format('D');

            if( $include_weekends_in_leave == 2 && ($curr == 'Sat' || $curr == 'Sun') ){// subtract if Saturday or Sunday
                $number_of_days--;
            }
            elseif( $include_holidays_in_leave == 2 &&
                ( in_array($dt->format('d-m-Y'), $holiday_dates1) || in_array($dt->format('d-m-Y'), $holiday_dates2) )
            ){ //exclude holidays
                $number_of_days--;
            }

        }


        return $number_of_days;

    }


    public static function calculate_time_btn_dates($starting_date,$ending_date){

        $startingDate = new DateTime($starting_date);
        $endingDate = new DateTime($ending_date);
        $interval = $startingDate->diff($endingDate);
        //echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";

        // shows the total amount of days (not divided into years, months and days like above)
        //echo "difference " . $interval->days . " days ";

        $number_of_days = $interval->days + 1;

        $time_interval = array(
            'number_of_days'   => $number_of_days,
            'number_of_months' => $interval->m,
            'number_of_years'  => $interval->y );

        return $time_interval;

    }


    public static function generate_dates_of_the_year($year){
        $all_dates = [];

        for($month=1; $month<=12; $month++){

            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for($day=1; $day<=$days_in_month; $day++){

                $full_date = strtotime($day.'-'.$month.'-'.$year);
                $all_dates[date('d-m-Y',$full_date)] =  date('D',$full_date);
            }
        }


        return $all_dates;
    }


}
