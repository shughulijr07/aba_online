<?php

namespace App\Models;

use App\Exports\ExcelExport;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Helper extends Model
{



    //convert date to format which is recognized by mysql
    public static function convert_date_to_mysql_format($origDate){

        $date = str_replace('/', '-', $origDate );
        $newDate = date("Y-m-d", strtotime($date));

        return $newDate;

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


    public static function calculate_moths_btn_dates($starting_date,$ending_date){

        $d1=new DateTime($starting_date);
        $d2=new DateTime($ending_date);
        $Months = $d2->diff($d1);
        $diff = (($Months->y) * 12) + ($Months->m);

        return $diff;

    }

    public static function get_previous_and_next_item_ids_in_a_table($table_name, $item_id){
        $ids = [ "previous_id" => null, "next_id" => null ];

        $previous_item = DB::table($table_name)->where('id', '<', $item_id)
            ->orderBy('id', 'desc')->first();
        $next_item = DB::table($table_name)->where('id', '>', $item_id)
            ->orderBy('id', 'asc')->first();

        if( isset($previous_item->id)){
            $ids[ "previous_id"] = $previous_item->id;
        }

        if( isset($next_item->id)){
            $ids[ "next_id"] = $next_item->id;
        }

        return $ids;
    }


    public static function getNumberOfDaysLeftToCompleteMonth($date, $position){

        $dateArray = explode("-",$date);
        $dateYear = $dateArray[0];
        $dateMonth = $dateArray[1];
        $dateDay = $dateArray[2];
        $numberOfDaysInMonth = date('t',strtotime($date));


        $numberOfDays = 0;
        if($position==="before"){
            $numberOfDays = $numberOfDaysInMonth - 1;
        }else{
            $numberOfDays = $numberOfDaysInMonth - $dateDay;
        }

        return $numberOfDays;

    }



    public static function send_sms_bongolive(array $recipients, $message){

        $formatted_recipients = [];

        $n = 1;
        foreach ($recipients as $recipient){
            $recipient = Utils::format_mobile_phone_number($recipient);
            $formatted_recipients[] = [
                'recipient_id' => $n,
                'dest_addr'=> $recipient
            ];

            $n++;
        }


        //.... replace <api_key> and <secret_key> with the valid keys obtained from the platform, under profile>authentication information
        //$api_key='<api_key>';
        //$secret_key = '<secret_key>'
        $api_key='72a9881b49b2344b';
        $secret_key = 'NTdkYTdmMjRmMDI4MWQ1ZmRkZDM2NGNjMjI3NmJiNGQzNzY0YWQ3NGUwOTY3NWYyMDAwNGZjMzk3OWI4MzAwNw==';
        // The data to send to the API
        $postData = array(
            'source_addr' => 'INFO',
            'encoding'=>0,
            'schedule_time' => '',
            'message' => $message,
            'recipients' => $formatted_recipients
        );

        //.... Api url
        $Url ='https://apisms.bongolive.africa/v1/send';

        // Setup cURL
        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for errors
        if($response === FALSE){
            echo $response;

            die(curl_error($ch));
        }
        //dd($response);


    }


    public static function format_mobile_phone_number($phone_no){

        $formatted_phone_no = '';
        $tanzania_code = '255';

        if(strlen($phone_no) >= 9){
            $phone_no = str_replace(' ','',$phone_no);
            $phone_no = substr($phone_no, -9);
            $formatted_phone_no = $tanzania_code.''.$phone_no;

        }

        return $formatted_phone_no;

    }


    public static function generate_random_password(){

        $chars = '10';
        $data = '@$1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        $random_text = substr(str_shuffle($data), 0, $chars);

        return $random_text;

    }


    public static function generate_alphabet_numeric_password(){

        $chars = '6';
        $data = '1234567890ABCDEFGHJKLMNPQRSTUVWXYZ';
        $random_text = substr(str_shuffle($data), 0, $chars);

        return $random_text;

    }


    public static function generate_complex_random_password(){

        $chars = '12';
        $data = '$@-{}[]_1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_text = substr(str_shuffle($data), 0, $chars);

        return $random_text;

    }

    public static function getNamesFromFullName($fullName){


        $grower_full_name = trim($fullName);
        $grower_full_name = preg_replace('!\s+!', ' ', $grower_full_name);//replace multiple spaces with single space
        $grower_full_name = strtoupper(strtolower($grower_full_name));
        $name_parts = explode(' ', $grower_full_name);

        $first_name = '';
        $middle_name = '';
        $last_name = '';

        if( count($name_parts) >= 3){
            $first_name = $name_parts[0];
            $middle_name = $name_parts[1];
            $last_name = $name_parts[2];
        }

        if( count($name_parts) == 2){
            $first_name = $name_parts[0];
            $last_name = $name_parts[1];
        }


        return [
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name
        ];


    }


    public static function generateRandomTime($minimum_time,$maximum_time){
        //$minimum_time = strtotime("00:00:00");
        //$maximum_time = strtotime("23:59:59");
        $minimum_time = strtotime($minimum_time);
        $maximum_time = strtotime($maximum_time);
        $rand = rand($minimum_time,$maximum_time);
        return date("H:i:s",$rand);
    }



}
