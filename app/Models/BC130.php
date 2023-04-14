<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BC130 extends Model
{
    private $connect = '';

    public function __construct(){
        $this->database_connection();
    }

    private function database_connection(){

        //$serverName = "DESKTOP-KBRVI86\\NAVDEMO"; //serverName\instanceName  // for testing
        $serverName = "TMARC002\\NAVDEMO"; //serverName\instanceName // live server
        $connectionInfo = array( "Database"=>"TMARC-NAV");
        $this->connect = sqlsrv_connect( $serverName, $connectionInfo);

    }


    /***************** TIME SHEET SECTION *****************/
    public function save_time_sheet_to_bc130($time_sheet){

        //save time sheet to bc130
        $feedback = $this->save_time_sheet($time_sheet);

        if( $feedback == 'success'){


            //save time sheet lines to bc130
            $time_sheet_lines = json_decode($time_sheet->json_lines->data);



            foreach ($time_sheet_lines as $line_details=>$value){

                //if line value is not null then save the line to bc130
                if($value != null){//only save lines which have values (not null)
                    $details = explode('--',$line_details);

                    $year = $time_sheet->year;
                    $month = str_pad($time_sheet->month, 2, '0', STR_PAD_LEFT);

                    $time_sheet_id = $time_sheet->id;
                    $line_type = $details[0];
                    $type_no = $details[1];
                    $day = $details[2];
                    $total_hrs = number_format($value,2);
                    $description = '';
                    $payroll_id = $year.'-'.$month;
                    $employee_no = $time_sheet->staff->staff_no;


                    //check if line have been saved before


                    $this->save_line($time_sheet_id,$line_type,$type_no,$day,$total_hrs,$description,$payroll_id,$employee_no);
                }

            }

                $time_sheet->transferred_to_nav = 'yes';
                $time_sheet->save();

        }



    }


    public function save_time_sheet($time_sheet){

        $feedback = '';

        //check if time sheet have been saved before
        $feedback2 = $this->check_if_time_sheet_have_been_saved($time_sheet);

        if($feedback2 == 'not saved yet'){


            $time_sheet_approvals = $time_sheet->approvals;

            //only save if time sheet have been approved properly
            if( count($time_sheet_approvals)>=2){

                //get required data from the line
                $time_sheet_id = $time_sheet->id;
                $employee_no = $time_sheet->staff->staff_no;
                $employee_name = ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name);
                $year = (integer) $time_sheet->year;
                $month = str_pad($time_sheet->month, 2, '0', STR_PAD_LEFT);
                $approving_spv = '';
                $approving_hrm = '';
                $spv_approval_date = '';
                $hrm_approval_date = '';
                $status = 'not-processed';

                foreach ($time_sheet_approvals as $approval){

                    if( $approval->level == 'spv' ){
                        $supervisor = Staff::find($approval->done_by);
                        $approving_spv = ucwords($supervisor->first_name.' '.$supervisor->last_name);
                        $spv_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                    if( $approval->level == 'hrm' ){
                        $hrm = Staff::find($approval->done_by);
                        $approving_hrm = ucwords($hrm->first_name.' '.$hrm->last_name);
                        $hrm_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                }


                $query = " INSERT INTO [dbo].[T-MARC\$Imported Time Sheets]
                        (	[Time Sheet Id]
                           ,[Employee  No]
                           ,[Employee Name]
                           ,[Year]
                           ,[Month]
                           ,[Approving Supervisor]
                           ,[Approving HRM]
                           ,[Supervisor Approval Date]
                           ,[HRM Approval Date]
                           ,[Status]
                        )

                        VALUES (
                            '$time_sheet_id',
                            '$employee_no',
                            '$employee_name',
                            '$year',
                            '$month',
                            '$approving_spv',
                            '$approving_hrm',
                            '$spv_approval_date',
                            '$hrm_approval_date',
                            '$status'
                        )";

                $stmt = sqlsrv_query( $this->connect, $query);
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                    $feedback = 'failed';

                }else{
                    $feedback = 'success';
                }


            }

        }else{
            $feedback = 'have been saved before';
        }

        return $feedback;
    }


    public function  save_line($time_sheet_id,$line_type,$type_no,$day,$total_hrs,$description,$payroll_id,$employee_no){


            //get required data from the line
            $day = (integer) $day;
            $total_hrs = (float) $total_hrs;


            $query = " INSERT INTO [dbo].[T-MARC\$Imported Time Sheet Lines]
                    (	[Time Sheet Id]
                       ,[Line Type]
                       ,[Type No_]
                       ,[Day]
                       ,[Total Hours]
                       ,[Description]
                       ,[Payroll ID]
                       ,[Employee No]
                    )
    
                    VALUES (
                        '$time_sheet_id',
                        '$line_type',
                        '$type_no',
                        '$day',
                        '$total_hrs',
                        '$description',
                        '$payroll_id',
                        '$employee_no'
                    )";

            $stmt = sqlsrv_query( $this->connect, $query);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );

            }else{
                $feedback = 'success';
            }


        return $feedback;

    }


    public function check_if_time_sheet_have_been_saved($time_sheet){

            $feedback = '';
            $query = "SELECT * FROM [dbo].[T-MARC\$Imported Time Sheets] 
    				  WHERE [Time Sheet Id] = '$time_sheet->id' ";

            $stmt = sqlsrv_query( $this->connect, $query);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            }

            $count = 0;
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

                $count++;

            }

            if($count>0){
                $feedback = 'have been saved before';
            }else{

                $feedback = 'not saved yet';
            }

            return $feedback;

    }




    /********************************* TRAVEL REQUESTS SECTION ****************/
    public function save_travel_request_to_bc130($travel_request){

        //save travel request to bc130
        $feedback = $this->save_travel_request($travel_request);
        //$feedback = 'success';

        if( $feedback == 'success'){


            //save travel request lines to bc130
            $travel_request_lines = json_decode(($travel_request->lines->last())->data);


            foreach ($travel_request_lines as $line){

                if( $line[3] == null || $line[3] == ''){ $line[3] =0;}
                if( $line[5] == null || $line[5] == ''){ $line[5] =0;}
                if( $line[6] == null || $line[6] == ''){ $line[6] =0;}
                if( $line[7] == null || $line[7] == ''){ $line[7] =0;}

                if(count($line) == 8){

                    $travel_request_id = $travel_request->id;
                    $account_code = $line[1];
                    $activity = $line[2];
                    $rate = $line[3];
                    $measure = $line[4];
                    $units = $line[5];
                    $percentage = $line[6];
                    $total_cost = $line[7];

                    //check if line have been saved before


                    $this->save_travel_request_line($travel_request_id,$account_code,$activity,$rate,$measure,$units,$percentage,$total_cost);
                }

            }

            $travel_request->transferred_to_nav = 'yes';
            $travel_request->save();

        }



    }


    public function save_travel_request($travel_request){

        $feedback = '';

        //check if travel request have been saved before
        $feedback2 = $this->check_if_travel_request_have_been_saved($travel_request);

        if($feedback2 == 'not saved yet'){


            $travel_request_approvals = $travel_request->approvals;

            //only save if travel request have been approved properly
            if( count($travel_request_approvals)>=4){

                //get required data from the line
                $travel_request_id = $travel_request->id;
                $employee_no = $travel_request->staff->staff_no;
                $employee_name = ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name);
                $project_code = $travel_request->project_code;
                $year = (integer) $travel_request->year;
                $departure_date = date('Y-m-d',strtotime($travel_request->departure_date));
                $purpose_of_trip = date('Y-m-d',strtotime($travel_request->returning_date));
                $returning_date = date('Y-m-d',strtotime($travel_request->purpose_of_trip));
                $approving_spv = '';
                $acc_approval_date = '';
                $approving_acc = '';
                $spv_approval_date = '';
                $approving_fd = '';
                $fd_approval_date = '';
                $approving_md = '';
                $md_approval_date = '';
                $status = 'not-processed';

                foreach ($travel_request_approvals as $approval){

                    if( $approval->level == 'spv' ){
                        $supervisor = Staff::find($approval->done_by);
                        $approving_spv = ucwords($supervisor->first_name.' '.$supervisor->last_name);
                        $spv_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                    if( $approval->level == 'acc' ){
                        $acc = Staff::find($approval->done_by);
                        $approving_acc = ucwords($acc->first_name.' '.$acc->last_name);
                        $acc_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                    if( $approval->level == 'fd' ){
                        $fd = Staff::find($approval->done_by);
                        $approving_fd = ucwords($fd->first_name.' '.$fd->last_name);
                        $fd_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                    if( $approval->level == 'md' ){
                        $md = Staff::find($approval->done_by);
                        $approving_md = ucwords($md->first_name.' '.$md->last_name);
                        $md_approval_date = date('Y-m-d H:i:s',strtotime($approval->created_at));

                    }
                }


                $query = " INSERT INTO [dbo].[T-MARC\$Imported Travel Requests]
                        (	[Travel Request Id]
                           ,[Employee No]
                           ,[Employee Name]
                           ,[Project Code]
                           ,[Year]
                           ,[Departure Date]
                           ,[Returning Date]
                           ,[Purpose Of Trip]
                           ,[Approving Supervisor]
                           ,[Supervisor Approval Date]
                           ,[Approving Accountant]
                           ,[Accountant Approval Date]
                           ,[Approving Finance Director]
                           ,[FD Approval Date]
                           ,[Approving Managind Director]
                           ,[MD Approval Date]
                           ,[Status]
                        )

                        VALUES (
                            '$travel_request_id',
                            '$employee_no',
                            '$employee_name',
                            '$project_code',
                            '$year',
                            '$departure_date',
                            '$returning_date',
                            '$purpose_of_trip',
                            '$approving_spv',
                            '$spv_approval_date',
                            '$approving_acc',
                            '$acc_approval_date',
                            '$approving_fd',
                            '$fd_approval_date',
                            '$approving_md',
                            '$md_approval_date',
                            '$status'
                        )";

                $stmt = sqlsrv_query( $this->connect, $query);
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                    $feedback = 'failed';

                }else{
                    $feedback = 'success';
                }


            }

        }else{
            $feedback = 'have been saved before';
        }

        return $feedback;
    }


    public function  save_travel_request_line($travel_request_id,$account_code,$activity,$rate,$measure,$units,$percentage,$total_cost){


        $rate = (float) $rate;
        $units = (float) $units;
        $percentage = (float) $percentage;
        $total_cost = (float) $total_cost;


        $query = " INSERT INTO [dbo].[T-MARC\$Imported Travel Request Lines]
                    (	[Travel Request Id]
                       ,[Account Code]
                       ,[Activity]
                       ,[Rate]
                       ,[Measure]
                       ,[Units]
                       ,[Percentage]
                       ,[Total Cost]
                    )
    
                    VALUES (
                        '$travel_request_id',
                        '$account_code',
                        '$activity',
                        '$rate',
                        '$measure',
                        '$units',
                        '$percentage',
                        '$total_cost'
                    )";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );

        }else{
            $feedback = 'success';
        }


        return $feedback;

    }


    public function check_if_travel_request_have_been_saved($travel_request){

        $feedback = '';
        $query = "SELECT * FROM [dbo].[T-MARC\$Imported Travel Requests] 
    				  WHERE [Travel Request Id] = '$travel_request->id' ";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

            $count++;

        }

        if($count>0){
            $feedback = 'have been saved before';
        }else{

            $feedback = 'not saved yet';
        }

        return $feedback;

    }




    /********************************* CUSTOMERS & ITEMS SECTION ****************/
    public function get_items_list(){

        $items = [];
        $query = "SELECT * FROM [dbo].[T-MARC\$Item]";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $items[] = $row;
            $count++;

        }

        return $items;

    }

    public function get_customers_list(){

        $customers = [];
        $query = "SELECT * FROM [dbo].[T-MARC\$Customer]";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $customers[] = $row;
            $count++;

        }

        return $customers;

    }

    public function get_sales_invoice_headers(){

        $customers = [];
        $query = "SELECT * FROM [dbo].[T-MARC\$Sales Invoice Header]";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $customers[] = $row;
            $count++;

        }

        return $customers;

    }

    public function get_sales_invoice_lines(){

        $customers = [];
        $query = "SELECT * FROM [dbo].[T-MARC\$Sales Invoice Line]";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $customers[] = $row;
            $count++;

        }

        return $customers;

    }

    public function get_value_entries(){

        $customers = [];
        $query = "SELECT * FROM [dbo].[T-MARC\$Value Entry]";

        $stmt = sqlsrv_query( $this->connect, $query);
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }

        $count = 0;
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $customers[] = $row;
            $count++;

        }

        return $customers;

    }




}
