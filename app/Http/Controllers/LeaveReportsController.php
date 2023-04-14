<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\MyFunctions;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class LeaveReportsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('access',['leave_reports','view'])){
            abort(403, 'Access Denied');
        }

        $all_staff = Staff::get_valid_staff_list();
        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        unset($leave_types['maternity_leave_2']);

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'index';

        return view('leave_reports.index',
            compact( 'all_staff', 'leave_types', 'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function generateReport(Request $request)
    {
        $data = $request->all();
        $report_type = $data['report_type'];
        $leave_type = $data['leave_type'];
        $staff_id = $data['staff_id'];
        $year= $data['year'];
        $month = $data['month'];
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];

        if($report_type == 'general' ){
            return $this->generate_general_report($leave_type,$staff_id,$from_date,$to_date);
        }

        if($report_type == 'leave-taken' ){
            return $this->generate_leave_taken_report($leave_type, $staff_id, $from_date, $to_date);
        }

        if($report_type == 'leave-rejected' ){
            return $this->generate_leave_rejected_report($leave_type, $staff_id, $from_date, $to_date);
        }

        if($report_type == 'leave-balances' ){
            return $this->generate_leave_balances_report($leave_type,$staff_id,$year);
        }

        if($report_type == 'leave-paid' ){
            return $this->generate_leave_paid_out_report($staff_id, $year);
        }


        if($report_type == 'leave-not-paid' ){
            return $this->generate_leave_not_paid_out_report($staff_id, $year);
        }

        if($report_type == 'overview' ){
            return $this->generate_overview_report($leave_type,$staff_id,$from_date, $to_date);
        }


    }


    public function generate_general_report($leave_type,$staff_id,$from_date,$to_date){

        //applied filters
        $year_filter = 'no';
        $month_filter = 'no';
        $from_date_filter = 'yes';
        $to_date_filter = 'yes';
        $employee_filter = 'yes';
        $leave_type_filter = 'yes';
        $report_title = 'GENERAL LEAVE REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $type_of_leave = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','=','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','staff.id as staff_id','staff.first_name','staff.last_name');

        //leave type filter
        if ( $leave_type == 'all') {
            //don't filter leave types
            $type_of_leave = 'All';
        }else{
            $query = $query->where('leaves.type','=',$leave_type);

            $type_of_leave = $leave_type;
        }


        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //from date filter
        if( $from_date == null ){
            $from_date = $default_year.'-01-01';
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }else{
            $from_date = MyFunctions::convert_date_to_mysql_format($from_date);
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }

        //to date filter
        if( $to_date == null ){
            $to_date = $default_year.'-12-31';
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }else{
            $to_date = MyFunctions::convert_date_to_mysql_format($to_date);
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }



        $leaves = $query->get();

        //dd($leaves);

        return view('leave_reports.general_report',
            compact('report_title','leaves','staff_name','type_of_leave','from_date','to_date','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));
    }


    public function generate_leave_taken_report($leave_type,$staff_id,$from_date,$to_date){

        //applied filters
        $year_filter = 'no';
        $month_filter = 'no';
        $from_date_filter = 'yes';
        $to_date_filter = 'yes';
        $employee_filter = 'yes';
        $leave_type_filter = 'yes';
        $report_title = 'LEAVE TAKEN REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $type_of_leave = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where( function ($query) { $query->where('status', '=', '40')->orWhere('status', '=', '50');} );

        //leave type filter
        if ( $leave_type == 'all') {
            //don't filter leave types
            $type_of_leave = 'All';
        }else{
            $query = $query->where('leaves.type','=',$leave_type);

            $type_of_leave = $leave_type;
        }


        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //from date filter
        if( $from_date == null ){
            $from_date = $default_year.'-01-01';
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }else{
            $from_date = MyFunctions::convert_date_to_mysql_format($from_date);
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }

        //to date filter
        if( $to_date == null ){
            $to_date = $default_year.'-12-31';
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }else{
            $to_date = MyFunctions::convert_date_to_mysql_format($to_date);
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }


        $leaves = $query->get();

        //dd($leaves);

        return view('leave_reports.leave_taken_report',
            compact('report_title','leaves','staff_name','type_of_leave','from_date','to_date','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));

    }


    public function generate_leave_rejected_report($leave_type,$staff_id,$from_date,$to_date){

        //applied filters
        $year_filter = 'no';
        $month_filter = 'no';
        $from_date_filter = 'yes';
        $to_date_filter = 'yes';
        $employee_filter = 'yes';
        $leave_type_filter = 'yes';
        $report_title = 'REJECTED LEAVES  REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $type_of_leave = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('status', '=', '99');

        //leave type filter
        if ( $leave_type == 'all') {
            //don't filter leave types
            $type_of_leave = 'All';
        }else{
            $query = $query->where('leaves.type','=',$leave_type);

            $type_of_leave = $leave_type;
        }


        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //from date filter
        if( $from_date == null ){
            $from_date = $default_year.'-01-01';
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }else{
            $from_date = MyFunctions::convert_date_to_mysql_format($from_date);
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }

        //to date filter
        if( $to_date == null ){
            $to_date = $default_year.'-12-31';
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }else{
            $to_date = MyFunctions::convert_date_to_mysql_format($to_date);
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }


        $leaves = $query->get();

        //dd($leaves);

        return view('leave_reports.rejected_leaves_report',
            compact('report_title','leaves','staff_name','type_of_leave','from_date','to_date','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));

    }


    public function generate_leave_balances_report($selected_leave_type,$staff_id,$year){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'no';
        $from_date_filter = 'no';
        $to_date_filter = 'no';
        $employee_filter = 'yes';
        $leave_type_filter = 'no';
        $report_title = 'LEAVE BALANCES  REPORT';

        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');

        $leave = new Leave();
        $leave_summaries = [];

        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        unset($leave_types['maternity_leave_2']);

        //staff id filter
        if ( is_numeric($staff_id)) {

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

            $summary =  $leave->get_leave_summary_for_staff($staff_id,$year);
            if( count($summary)>0 ){
                $summary['staff-info'] = $staff;
                $leave_summaries[] = $summary;
            }

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';
            $all_staff = [];

            if ( $staff_id == 'all') {

                $all_staff = Staff::get_valid_staff_list();

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $all_staff = Staff::get_valid_staff_list_by_status($staff_status);
            }

            Staff::get_valid_staff_list_by_status('Active');

            foreach ($all_staff as $staff) {

                $summary =  $leave->get_leave_summary_for_staff($staff->id,$year);
                if( count($summary)>0 ){
                    $summary['staff-info'] = $staff;
                    $leave_summaries[] = $summary;
                }
            }
        }


        if ( $staff_id == 'all') {

            $staff_name = 'All Employees';

        }else{
        }

        //dd($selected_leave_type);
        $view = 'leave_reports.leave_balances_report';
        if( $selected_leave_type == 'annual_leave' ){
            $view = 'leave_reports.leave_balances_report_annual';
            $report_title = 'ANNUAL LEAVE BALANCES  REPORT';
        }else{
            $view = 'leave_reports.leave_balances_report';
        }

        //dd($leave_summaries);

        return view($view,
            compact('report_title','leave_summaries','staff_name','year','generated_by','selected_leave_type','leave_types',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'generation_date'));


    }


    public function generate_leave_paid_out_report($staff_id,$year){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'no';
        $from_date_filter = 'no';
        $to_date_filter = 'no';
        $employee_filter = 'yes';
        $leave_type_filter = 'no';
        $report_title = 'PAID OUT LEAVES REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','leaves.paid_by_accountant','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('status', '=', '4');
        $query = $query->where('payment', '=', 'Include');
        $query = $query->where('paid_by_accountant', '=', 'yes');

        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }


        $leaves = $query->get();

        //dd($leaves);

        return view('leave_reports.paid_out_report',
            compact('report_title','leaves','staff_name','generated_by', 'year',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));

    }


    public function generate_leave_not_paid_out_report($staff_id,$year){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'no';
        $from_date_filter = 'no';
        $to_date_filter = 'no';
        $employee_filter = 'yes';
        $leave_type_filter = 'no';
        $report_title = 'NOT PAID OUT LEAVES REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','leaves.paid_by_accountant','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('status', '=', '3');
        $query = $query->where('payment', '=', 'Include');
        $query = $query->where('paid_by_accountant', '=', 'no');

        //staff id filter
        if ( $staff_id == 'all') {
            //don't filter staffs
            $staff_name = 'All Employees';

        }else{
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);
        }


        $leaves = $query->get();

        //dd($leaves);

        return view('leave_reports.paid_out_report',
            compact('report_title','leaves','staff_name','generated_by', 'year',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));

    }


    public function generate_overview_report($leave_type,$staff_id,$from_date, $to_date){

        //applied filters
        $year_filter = 'no';
        $month_filter = 'no';
        $from_date_filter = 'yes';
        $to_date_filter = 'yes';
        $employee_filter = 'yes';
        $leave_type_filter = 'yes';
        $report_title = 'LEAVE OVERVIEW REPORT';

        $default_year = date('Y');
        $staff_name = '';
        $type_of_leave = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $query = Leave::query();
        $query = $query->join('staff','staff.id','leaves.employee_id');
        $query = $query->select('leaves.id','leaves.starting_date','leaves.ending_date','leaves.type','leaves.status','leaves.created_at','staff.id as staff_id','staff.first_name','staff.last_name');

        //leave type filter
        if ( $leave_type == 'all') {
            //don't filter leave types
            $type_of_leave = 'All';
        }else{
            $query = $query->where('leaves.type','=',$leave_type);

            $type_of_leave = $leave_type;
        }


        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('leaves.employee_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{
            //don't filter staffs
            $staff_name = 'All Employees';

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $staff_name = 'All '.$staff_status.' Employees';
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //from date filter
        if( $from_date == null ){
            $from_date = $default_year.'-01-01';
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }else{
            $from_date = MyFunctions::convert_date_to_mysql_format($from_date);
            $query = $query->where('leaves.starting_date','>=',$from_date);
        }

        //to date filter
        if( $to_date == null ){
            $to_date = $default_year.'-12-31';
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }else{
            $to_date = MyFunctions::convert_date_to_mysql_format($to_date);
            $query = $query->where('leaves.ending_date','<=',$to_date);
        }


        $leaves = $query->get();


        $overviews = $this->get_leaves_overview($leaves);

        //dd($leaves);

        return view('leave_reports.overview_report',
            compact('report_title','overviews','staff_name','type_of_leave','from_date','to_date','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'leave_type_filter',
                'leave_statuses','leave_types','generation_date'));


    }


    public function get_leaves_overview($leaves){

        $overview = [
            'number_of_requests' => 0,
            'waiting_for_spv_approval' => 0,
            'waiting_for_hrm_approval' => 0,
            'waiting_for_md_approval' => 0,
            'waiting_for_payment' => 0,
            'approved' => 0,
            'rejected' => 0,

        ];


        $overviews = [];
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        //create list of overviews dynamically
        foreach($leave_types as $key=>$leave_type){
            $overviews[$key] = $overview;
        }


        foreach ($leaves as $leave){

            $overviews[$leave->type]['number_of_requests'] += 1;


            switch($leave->status){
                case '10': $overviews[$leave->type]['waiting_for_spv_approval'] += 1; break;
                case '20': $overviews[$leave->type]['waiting_for_hrm_approval'] += 1; break;
                case '30': $overviews[$leave->type]['waiting_for_md_approval'] += 1; break;
                case '40': $overviews[$leave->type]['waiting_for_payment'] += 1; break;
                case '50': $overviews[$leave->type]['approved'] += 1; break;
                case '99': $overviews[$leave->type]['rejected'] += 1; break;

            }

        }

        //dd($overviews);

        return $overviews;
    }


}
