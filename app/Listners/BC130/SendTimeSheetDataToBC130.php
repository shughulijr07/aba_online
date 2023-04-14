<?php

namespace App\Listners\BC130;

use App\Models\BC130;
use App\Events\SendTimeSheetToBC130;
use App\Models\TimeSheet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTimeSheetDataToBC130
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendTimeSheetToBC130  $event
     * @return void
     */
    public function handle(SendTimeSheetToBC130 $event)
    {


        //get all time sheets which have not been sent to nav
        $time_sheets = TimeSheet::where('status','=','50')->where('transferred_to_nav','=','no')->get();

        if( count($time_sheets)>0){
            $bc130 = new BC130();
            foreach ($time_sheets as $time_sheet) {
                $bc130->save_time_sheet_to_bc130($time_sheet);
            }
        }

        //dd('pause');
    }





    public function send_time_sheet_to_bc130($timeSheet){

        //format time sheet data
        $timeSheetHeader = [
            'Id' => $timeSheet->id,
            'Employee_No' => 5,
            'Year' => 2020,
            'Month' => 04,
            'Approving_Supervisor' => 'EUR',
            'Approving_HRM' => 'NL',
            'Approving_MD' => 'BINNENLAND',
            'Supervisor_Approval Date' => '04/20/2020',
            'HRM_Approval_Date' => '04/20/2020',
            'MD_Approval_Date' => '04/20/2020',
        ];

        $customer = [
            'Name' => 'WebServiceTestCustomer',
            'Phone_No' => '016666666',
            'Post_Code' => '3000',
            'Country_Region_Code' => 'BE',
            'Currency_Code' => 'EUR',
            'Language_Code' => 'NL',
            'Customer_Posting_Group' => 'BINNENLAND',
            'Gen_Bus_Posting_Group' => 'BINNENLAND',
            'VAT_Bus_Posting_Group' => 'BINNENLAND',
            'Payment_Terms_Code' => '14 DAGEN',
            'Reminder_Terms_Code' => 'NEDERLANDS'
        ];

        //$timeSheetHeader = json_encode($timeSheet);

        //dd($timeSheetHeader);
        //$this->curl($timeSheetHeader);
        //$this->curl($timeSheetHeader);

        $this->read_data();

    }

    public function curl($post_fields){

        $url = 'http://DESKTOP-KBRVI86:7048/BC130/OData/Company(\'T-MARC\')/Import_Time_Sheet';
        //$url = 'http://DESKTOP-KBRVI86:7048/BC130/ODataV4/Company(\'T-MARC\')/Custome_Card';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_fields));
        curl_setopt($ch, CURLOPT_USERPWD, 'KBRVI86\Susuma:Puro@1990');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Connection: Keep-Alive',
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
            "Accept: */*"
        ]);

        //dd($ch);

        $response = json_decode(curl_exec($ch), TRUE);
        echo json_encode($response, JSON_PRETTY_PRINT);

        // Close handle
        curl_close($ch);

    }



    public function read_data(){

        $url = 'http://DESKTOP-KBRVI86:7048/BC130/OData/Company(\'T-MARC\')/Customers';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);

       //curl_setopt($ch, CURLOPT_USERPWD, 'DESKTOP-KBRVI86\SUSUMA:Puro@1990');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json; charset=utf-8',
        ]);

        $response = json_decode(curl_exec($ch), TRUE);
        echo json_encode($response, JSON_PRETTY_PRINT);
        // Close handle
        curl_close($ch);

    }



}
