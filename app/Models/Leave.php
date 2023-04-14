<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;
use DatePeriod;

class Leave extends Model
{
    protected $guarded = [];

    public static $leave_types = [
        'annual-leave' => 'Annual Leave',
        'sick-leave' => 'Sick leave',
        'maternity-leave' => 'Maternity leave',
        'paternity-leave' => 'Paternity leave',
        'compassionate-leave' => 'Compassionate leave',
        'study-leave' => 'Study Leave',
    ];


    public static $leave_statuses = [
        '0'  => 'Returned For Correction',
        '10' => 'Waiting For Supervisor Approval',
        '20' => 'Waiting For HRM Approval',
        '30' => 'Waiting For MD Approval',
        '40' => 'Approved & Waiting For Payment',
        '50' => 'Approved',
        '99' => 'Rejected',
    ];


    public static $babies_count = [
        '1' => 'One',
        '2' => 'Two',
        '3' => 'Three',
        '4' => 'Four',
        '5' => 'Five',
        '6' => 'Six',
    ];




    public function staff(){
        return $this->belongsTo(Staff::class, 'employee_id','id');
    }


    public function leave_payment(){
        return $this->hasOne(LeavePayment::class);
    }


    public function supervisor_changes(){
        return $this->hasMany(LeaveChangedSupervisor::class);
    }


    public function leave_approvals(){
        return $this->hasMany(LeaveApproval::class);
    }


    public function leave_modifications(){
        return $this->hasMany(LeaveModification::class);
    }


    public static function get_responsible_supervisor($supervisor_id){
        $responsible_spv = Staff::where('id','=',$supervisor_id)->first();
        return $responsible_spv;

    }


    public function leave_reject(){
        return $this->hasOne(LeaveReject::class);
    }


    public function get_taken_leaves($employee_id,$leave_type,$year){

        $leave_taken = [
            'leaves' => [],
            'days_taken' => 0,
        ];

        $leaves = Leave::where('employee_id', '=', $employee_id)
                        ->where('type','=',$leave_type)
                        ->where('year','=',$year)
                        ->where('status','=','50')->get();

        if( count($leaves) > 0 ){

            foreach ($leaves as $leave){
                $leave_taken['days_taken'] += $this->calculate_no_of_days_btn_dates($leave->starting_date, $leave->ending_date);
            }

        }

        return $leave_taken;

    }


    public function get_staff_leaves_by_status($employee_id,$leave_type,$year,$status){

        $leave_info = ['leaves' => [], 'total_days'=> 0];

        $leaves = Leave::where('employee_id', '=', $employee_id)
                    ->where('type','=',$leave_type)
                    ->where('year','=',$year)
                    ->where('status','=',$status)->get();

        $leave_info['leaves'] = $leaves;


        if( count($leaves) > 0 ){

            foreach ($leaves as $leave){
                $leave_info['total_days'] += $this->calculate_no_of_days_btn_dates($leave->starting_date, $leave->ending_date);
            }

        }

        return $leave_info;

    }


    public function check_if_payment_have_been_issued_for_annual_leave($staff_id,$year){


        $leaves = Leave::where('employee_id', '=', $staff_id)
            ->where('type','=','annual_leave')
            ->where('year','=',$year)
            ->where('payment','=','Include')
            ->where( function ($query) { $query->where('status', '=', '40')->orWhere('status', '=', '50');} )->get()->count();

        //dd($leaves);
        if($leaves > 0){
            $payment  = 'Have Been Issued';
        }else{
            $payment  = 'Have Not Been Issued';
        }

        return $payment;

    }


    public function get_leave_summary_for_staff($staff_id,$year = ''){

        $year =  $year == '' ? $year = date('Y') : $year;

        $leave_entitlement = LeaveEntitlement::get_leave_entitlements_by_year($staff_id, $year)['arrays'];

        $summary = [];

        if( count($leave_entitlement) > 0 ){

            //annual leave
            $annual_leaves_waiting_for_spv_approval       = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '10');
            $annual_leaves_waiting_for_hrm_approval       = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '20');
            $annual_leaves_waiting_for_md_approval        = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '30');
            $annual_leaves_waiting_payment                = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '40');
            $annual_leaves_approved                       = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '50');
            $annual_leaves_rejected                       = $this->get_staff_leaves_by_status($staff_id, 'annual_leave', $year, '99');
            $annual_leave_days_taken                      = $annual_leaves_waiting_payment['total_days'] + $annual_leaves_approved['total_days'];
            $annual_leave_days_left                       = $leave_entitlement['annual_leave'] - $annual_leave_days_taken;
            $annual_leave_payment                         = $this->check_if_payment_have_been_issued_for_annual_leave($staff_id, $year);

            //Sick leave
            $sick_leaves_waiting_for_spv_approval         = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '10');
            $sick_leaves_waiting_for_hrm_approval         = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '20');
            $sick_leaves_waiting_for_md_approval          = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '30');
            $sick_leaves_waiting_payment                  = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '40');
            $sick_leaves_approved                         = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '50');
            $sick_leaves_rejected                         = $this->get_staff_leaves_by_status($staff_id, 'sick_leave', $year, '99');
            $sick_leave_days_taken                        = $sick_leaves_waiting_payment['total_days'] + $sick_leaves_approved['total_days'];
            $sick_leave_days_left                         = $leave_entitlement['sick_leave'] - $sick_leave_days_taken;

            //Maternity leave
            $maternity_leave_waiting_for_spv_approval     = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '10');
            $maternity_leave_waiting_for_hrm_approval     = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '20');
            $maternity_leave_waiting_for_md_approval      = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '30');
            $maternity_leave_waiting_payment              = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '40');
            $maternity_leave_approved                     = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '50');
            $maternity_leave_rejected                     = $this->get_staff_leaves_by_status($staff_id, 'maternity_leave', $year, '99');
            $maternity_leave_days_taken                   = $maternity_leave_waiting_payment['total_days'] + $maternity_leave_approved['total_days'];
            $maternity_leave_days_left                    = $leave_entitlement['maternity_leave'] - $maternity_leave_days_taken;


            //Paternity leave
            $paternity_leave_waiting_for_spv_approval     = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '10');
            $paternity_leave_waiting_for_hrm_approval     = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '20');
            $paternity_leave_waiting_for_md_approval      = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '30');
            $paternity_leave_waiting_payment              = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '40');
            $paternity_leave_approved                     = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '50');
            $paternity_leave_rejected                     = $this->get_staff_leaves_by_status($staff_id, 'paternity_leave', $year, '99');
            $paternity_leave_days_taken                   = $paternity_leave_waiting_payment['total_days'] + $paternity_leave_approved['total_days'];
            $paternity_leave_days_left                    = $leave_entitlement['paternity_leave'] - $paternity_leave_days_taken;

            //Compassionate Leave
            $compassionate_leave_waiting_for_spv_approval = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '10');
            $compassionate_leave_waiting_for_hrm_approval = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '20');
            $compassionate_leave_waiting_for_md_approval  = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '30');
            $compassionate_leave_waiting_payment          = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '40');
            $compassionate_leave_approved                 = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '50');
            $compassionate_leave_rejected                 = $this->get_staff_leaves_by_status($staff_id, 'compassionate_leave', $year, '99');
            $compassionate_leave_days_taken               = $compassionate_leave_waiting_payment['total_days'] + $compassionate_leave_approved['total_days'];
            $compassionate_leave_days_left                = $leave_entitlement['compassionate_leave'] - $compassionate_leave_days_taken;

            //Other
            $other_leave_waiting_for_spv_approval         = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '10');
            $other_leave_waiting_for_hrm_approval         = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '20');
            $other_leave_waiting_for_md_approval          = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '30');
            $other_leave_waiting_payment                  = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '40');
            $other_leave_approved                         = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '50');
            $other_leave_rejected                         = $this->get_staff_leaves_by_status($staff_id, 'other', $year, '99');
            $other_leave_days_taken                       = $other_leave_waiting_payment['total_days'] + $other_leave_approved['total_days'];
            $other_leave_days_left                        = $leave_entitlement['other'] - $other_leave_days_taken;




            $summary = [
                'annual_leave'=> [
                    'waiting-spv-approval' => $annual_leaves_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $annual_leaves_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $annual_leaves_waiting_for_md_approval,
                    'waiting-payment'      => $annual_leaves_waiting_payment,
                    'approved'             => $annual_leaves_approved,
                    'rejected'             => $annual_leaves_rejected,
                    'days-taken'           => $annual_leave_days_taken,
                    'days-left'            => $annual_leave_days_left,
                    'entitled-days'        => $leave_entitlement['annual_leave'],
                    'payment'              => $annual_leave_payment,
                ],
                'sick_leave'=> [
                    'waiting-spv-approval' => $sick_leaves_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $sick_leaves_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $sick_leaves_waiting_for_md_approval,
                    'waiting-payment'      => $sick_leaves_waiting_payment,
                    'approved'             => $sick_leaves_approved,
                    'rejected'             => $sick_leaves_rejected,
                    'days-taken'           => $sick_leave_days_taken,
                    'days-left'            => $sick_leave_days_left,
                    'entitled-days'        => $leave_entitlement['sick_leave'],
                ],
                'maternity_leave'=> [
                    'waiting-spv-approval' => $maternity_leave_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $maternity_leave_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $maternity_leave_waiting_for_md_approval,
                    'waiting-payment'      => $maternity_leave_waiting_payment,
                    'approved'             => $maternity_leave_approved,
                    'rejected'             => $maternity_leave_rejected,
                    'days-taken'           => $maternity_leave_days_taken,
                    'days-left'            => $maternity_leave_days_left,
                    'entitled-days'        => $leave_entitlement['maternity_leave'],
                ],
                'paternity_leave'=> [
                    'waiting-spv-approval' => $paternity_leave_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $paternity_leave_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $paternity_leave_waiting_for_md_approval,
                    'waiting-payment'      => $paternity_leave_waiting_payment,
                    'approved'             => $paternity_leave_approved,
                    'rejected'             => $paternity_leave_rejected,
                    'days-taken'           => $paternity_leave_days_taken,
                    'days-left'            => $paternity_leave_days_left,
                    'entitled-days'        => $leave_entitlement['paternity_leave'],
                ],
                'compassionate_leave'=> [
                    'waiting-spv-approval' => $compassionate_leave_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $compassionate_leave_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $compassionate_leave_waiting_for_md_approval,
                    'waiting-payment'      => $compassionate_leave_waiting_payment,
                    'approved'             => $compassionate_leave_approved,
                    'rejected'             => $compassionate_leave_rejected,
                    'days-taken'           => $compassionate_leave_days_taken,
                    'days-left'            => $compassionate_leave_days_left,
                    'entitled-days'        => $leave_entitlement['compassionate_leave'],
                ],
                'other'=> [
                    'waiting-spv-approval' => $other_leave_waiting_for_spv_approval,
                    'waiting-hrm-approval' => $other_leave_waiting_for_hrm_approval,
                    'waiting-md-approval'  => $other_leave_waiting_for_md_approval,
                    'waiting-payment'      => $other_leave_waiting_payment,
                    'approved'             => $other_leave_approved,
                    'rejected'             => $other_leave_rejected,
                    'days-taken'           => $other_leave_days_taken,
                    'days-left'            => $other_leave_days_left,
                    'entitled-days'        => $leave_entitlement['other'],
                ],


            ];

        }

        return $summary;

    }


    public function calculate_no_of_days_btn_dates($starting_date,$ending_date){

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


    public function calculate_no_of_days_btn_dates_og($starting_date,$ending_date){

        $starting_date = str_replace('/', '-', $starting_date);
        $ending_date = str_replace('/', '-', $ending_date);

        $startingDate = new DateTime($starting_date);
        $endingDate = new DateTime($ending_date);
        $interval = $startingDate->diff($endingDate);
        //echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";

        // shows the total amount of days (not divided into years, months and days like above)
        //echo "difference " . $interval->days . " days ";

        $number_of_days = $interval->days + 1;

        return $number_of_days;

    }


    public function calculate_time_btn_dates($starting_date,$ending_date){

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


    public static function countLeaveRequests(){
        $user = auth()->user();

        $employee_id = 0;
        $current_user_role = $user->role_id;

        if($user->category == "staff"){
            $employee_id = $user->staff->id;
        }


        //for my requests
        $waitingForSPVApproval  = Leave::where('status', '=', '10')->where('employee_id','=',$employee_id)->get()->count();
        $waitingForHRMApproval  = Leave::where('status', '=', '20')->where('employee_id','=',$employee_id)->get()->count();
        $waitingForMDApproval   = Leave::where('status', '=', '30')->where('employee_id','=',$employee_id)->get()->count();
        $approvedWaitingPayment = Leave::where('status', '=', '40')->where('employee_id','=',$employee_id)->get()->count();
        $approved               = Leave::where('status', '=', '50')->where('employee_id','=',$employee_id)->get()->count();
        $rejected               = Leave::where('status', '=', '99')->where('employee_id','=',$employee_id)->get()->count();

        //for approvals
        $waitingForSPVApproval2  = 0;
        $waitingForHRMApproval2  = 0;
        $waitingForMDApproval2   = 0;
        $approvedWaitingPayment2 = 0;
        $approved2               = 0;
        $rejected2               = 0;


        if(in_array($current_user_role,[1,2,3,4])){ //for Super Administrator, MD, HRM or Accountant
            $waitingForSPVApproval2  = Leave::where('status', '=', '10')->get()->count();
            $waitingForHRMApproval2  = Leave::where('status', '=', '20')->get()->count();
            $waitingForMDApproval2   = Leave::where('status', '=', '30')->get()->count();
            $approvedWaitingPayment2 = Leave::where('status', '=', '40')->get()->count();
            $approved2               = Leave::where('status', '=', '50')->get()->count();
            $rejected2               = Leave::where('status', '=', '99')->get()->count();
        }

        if(in_array($current_user_role,[5,9])){ // for SPV
            $waitingForSPVApproval2  = Leave::where('status', '=', '10')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForHRMApproval2  = Leave::where('status', '=', '20')->where('responsible_spv','=',$employee_id)->get()->count();
            $waitingForMDApproval2   = Leave::where('status', '=', '30')->where('responsible_spv','=',$employee_id)->get()->count();
            $approvedWaitingPayment2 = Leave::where('status', '=', '40')->where('responsible_spv','=',$employee_id)->get()->count();
            $approved2               = Leave::where('status', '=', '50')->where('responsible_spv','=',$employee_id)->get()->count();
            $rejected2               = Leave::where('status', '=', '99')->where('responsible_spv','=',$employee_id)->get()->count();
        }

        $applications = [
            'waitingForSPVApproval'  => $waitingForSPVApproval,
            'waitingForHRMApproval'  => $waitingForHRMApproval,
            'waitingForMDApproval'   => $waitingForMDApproval,
            'approvedWaitingPayment' => $approvedWaitingPayment,
            'approved'               => $approved,
            'rejected'               => $rejected,
            'waitingForSPVApproval2' => $waitingForSPVApproval2,
            'waitingForHRMApproval2' => $waitingForHRMApproval2,
            'waitingForMDApproval2'  => $waitingForMDApproval2,
            'approvedWaitingPayment2'=> $approvedWaitingPayment2,
            'approved2'              => $approved2,
            'rejected2'              => $rejected2,
        ];

        return $applications;
    }


    public function get_overlapping_leaves($identifier,$leave_id,$department,$starting_date,$ending_date){
        $in_all_departments = [];
        $in_same_department = [];
        $starting_date = MyFunctions::convert_date_to_mysql_format($starting_date);
        $ending_date = MyFunctions::convert_date_to_mysql_format($ending_date);

        $query = Leave::query();

        //overlapping_starting_date
        if($identifier == 'overlapping_starting_date'){
            $query = $query->where('starting_date', '<=', $starting_date);
            $query = $query->where('ending_date', '>', $starting_date);
            $query = $query->where('ending_date', '<', $ending_date);
        }

        if($identifier == 'overlapping_ending_date'){
            $query = $query->where('ending_date', '>=', $ending_date);
            $query = $query->where('starting_date', '>=', $starting_date);
            $query = $query->where('starting_date', '<', $ending_date);
        }

        if($identifier == 'overlapping_in'){
            $query = $query->where('starting_date', '>', $starting_date);
            $query = $query->where('ending_date', '<', $ending_date);
        }

        if($identifier == 'overlapping_out'){
            $query = $query->where('starting_date', '<', $starting_date);
            $query = $query->where('ending_date', '>', $ending_date);
        }

        if($identifier == 'overlapping_exactly_on_same_dates'){
            $query = $query->where('starting_date', '=', $starting_date);
            $query = $query->where('ending_date', '=', $ending_date);
        }


        $leaves = $query->get();

        if( count($leaves) > 0 ){
            foreach ($leaves as $leave){

                if ($leave_id == 'all') { //take all returned leave requests
                    //check for overlapping leaves from same department
                    if ($leave->staff->department->id == $department) {
                        $in_same_department[] = $leave;
                    }else{
                        //overlapping from all departments
                        $in_all_departments[] = $leave;
                    }
                }else{ //exclude the leave request we are comparing to

                    if ($leave->id != $leave_id) {
                        //check for overlapping leaves from same department
                        if ($leave->staff->department->id == $department) {
                            $in_same_department[] = $leave;
                        }else{
                            //overlapping from all departments
                            $in_all_departments[] = $leave;
                        }
                    }

                }

            }
        }

        $overlapping = array('all-departments' => $in_all_departments,'same-department' => $in_same_department);
        //dump($overlapping);

        return $overlapping;

    }


    public static function get_staff_leaves_in_a_month($leave_type,$staff_id,$year,$month){

        //dd($year.', '.$month);
        $days_in_month =  cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $starting_date = $year.'-'.$month.'-1';
        $ending_date = $year.'-'.$month.'-'.$days_in_month;

        $staff_leaves = Leave::where('employee_id','=',$staff_id)
            ->where('type','=',$leave_type)
            ->where('status','=','50')
            ->where('year','=',$year)
            ->where(
                function ($query) use ($starting_date,$ending_date) {
                    $query->where('starting_date','>=',$starting_date)
                          ->orwhere('ending_date','<=',$ending_date);
                } )
            ->get();

        return $staff_leaves;

    }


    public static function get_all_dates_of_staff_leave_in_a_month($leave_type,$staff_id,$year,$month){

        $leave_dates = [];

        $staff_leaves = self::get_staff_leaves_in_a_month($leave_type,$staff_id,$year,$month);


        if( count($staff_leaves) > 0){

            foreach ( $staff_leaves as $leave){

               //dump($leave->starting_date.' | '.$leave->ending_date);

                $begin = new DateTime( $leave->starting_date );
                $end = new DateTime( $leave->ending_date );
                $end = $end->modify( '+1 day' ); //Include the last day

                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($begin, $interval ,$end);

                foreach ($daterange as $key => $date) {
                    $leave_dates[] = $date->format('d-m-Y');
                }

            }
        }

        $leave_dates = array_unique($leave_dates);
        //dd($leave_dates);
        return $leave_dates;
    }


    public static function extend_normal_maternity_leave_for_staff($staff_id,$year,$number_of_babies){

        $entitlement = LeaveEntitlement::where('staff_id','=',$staff_id)
                                       ->where('year','=',$year)->first();

        $lines = $entitlement->lines;
        $maternity_leave_line = '';
        $maternity_leave_line2 = '';

        foreach ($lines as $line){
            if( $line->type_of_leave == 'maternity_leave'){
                $maternity_leave_line  = $line;
                $maternity_leave_line2 = $line;
            }

            if($number_of_babies > 1 && $line->type_of_leave == 'maternity_leave_2'){
                $maternity_leave_line2 = $line;
            }

        }


        //extend
        if($maternity_leave_line2->number_of_days > $maternity_leave_line->number_of_days){

            $maternity_leave_line->number_of_days = $maternity_leave_line2->number_of_days;
            $maternity_leave_line->save();

            //record extension
            $extended_days = $maternity_leave_line2->number_of_days - $maternity_leave_line->number_of_days;

            $annual_leave_extension = new LeaveEntitlementExtension();
            $annual_leave_extension->leave_entitlement_line_id = $maternity_leave_line->id;
            $annual_leave_extension->no_days = $extended_days;
            $annual_leave_extension->done_by = auth()->user()->staff->id;
            $annual_leave_extension->reason = 'Created automatically due number of babies being more than one';
            $annual_leave_extension->save();
        }

        //but if number of babies is one, just reset it
        if($number_of_babies == 1){

            //re-set to basic basic number of days for maternity leave
            $maternity_leave_line->number_of_days = (LeaveType::where('key','=','maternity_leave')->first())->days;
            $maternity_leave_line->save();

            //record extension if any
            $extended_days = $maternity_leave_line->number_of_days - $maternity_leave_line2->number_of_days;

            if($extended_days != 0){
                $annual_leave_extension = new LeaveEntitlementExtension();
                $annual_leave_extension->leave_entitlement_line_id = $maternity_leave_line->id;
                $annual_leave_extension->no_days = $extended_days;
                $annual_leave_extension->done_by = auth()->user()->staff->id;
                $annual_leave_extension->reason = 'Created due to decrease in  number of babies from multiple to one';
                $annual_leave_extension->save();
            }

        }


    }


    public static function request_with_similar_dates_made_by_same_staff($staff_id,$starting_date,$ending_date){


        $count = 0;

        $overlapping_starting_date = Leave::where('employee_id','=',$staff_id)
            ->where('status','<>','99')
            ->where('starting_date','<=',$starting_date)
            ->where('ending_date','>=',$starting_date)
            ->get();

        $overlapping_ending_date = Leave::where('employee_id','=',$staff_id)
            ->where('status','<>','99')
            ->where('starting_date','<=',$ending_date)
            ->where('ending_date','>=',$ending_date)
            ->get();

        $count += count($overlapping_starting_date) + count($overlapping_ending_date);


        return $count;

    }

}
