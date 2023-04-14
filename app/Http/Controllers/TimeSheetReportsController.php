<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Project;
use App\Models\TimeSheet;
use App\Models\TimeSheetApproval;
use App\Models\TimeSheetLateSubmission;
use App\Models\TimeSheetType;
use App\Models\MyFunctions;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class TimeSheetReportsController extends Controller
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
        if (Gate::denies('access',['time_sheet_reports','view'])){
            abort(403, 'Access Denied');
        }

        $all_staff = Staff::get_valid_staff_list();

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'index';

        return view('time_sheet_reports.index',
            compact( 'all_staff', 'year', 'initial_year',
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
        $staff_id = $data['staff_id'];
        $year= $data['year'];
        $month = $data['month'];

        if($report_type == 'overview' ){
            return $this->generate_overview_report($staff_id,$year, $month);
        }

        if($report_type == 'detailed' ){
            return $this->generate_detailed_report($staff_id,$year,$month);
        }

        if($report_type == 'submitted' ){
            return $this->generate_employees_submitted_timesheets_report($staff_id,$year,$month);
        }

        if($report_type == 'submitted-loe' ){
            return $this->generate_employees_submitted_timesheets_with_loe_report($staff_id,$year,$month);
        }

        if($report_type == 'not-submitted' ){
            return $this->generate_employees_not_submitted_timesheets_report($staff_id,$year,$month);
        }

        if($report_type == 'late-submitted' ){
            return $this->generate_employees_late_submitted_timesheets_report($staff_id,$year,$month);
        }


        if($report_type == 'ontime-submitted' ){
            return $this->generate_employees_ontime_submitted_timesheets_report($staff_id,$year, $month);
        }


        if(in_array($report_type, ['waiting-spv-approval', 'waiting-hrm-approval', 'in-drafts', 'returned-for-correction', 'rejected' ]) ){
            return $this->generate_partially_submitted_timesheets_report($report_type, $staff_id,$year, $month);
        }

        if($report_type == 'not-filled-at-all' ){
            return $this->generate_employees_not_filled_timesheets_at_all_report($staff_id,$year,$month);
        }

        if($report_type == 'project-hrs-ratio' ){
            return $this->generate_projects_hrs_ratio_report($staff_id,$year,$month);
        }


    }


    public function generate_overview_report($staff_id,$year, $month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "TIME SHEETS SUBMISSION OVERVIEW REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        //staff id filter
        if ( $staff_id == 'all') {
            //don't filter staffs
            $staff_name = 'All Employees';

        }else{
        }
        if ( is_numeric($staff_id)) {
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
            }
        }


        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{

        }

        //moth filter
        if( $month == 'all' ){

        }else{

        }


        $overview = [
            'submitted'                 => $this->get_submitted_timesheets($staff_id,$year,$month),
            'not-submitted'             => $this->get_employees_not_submitted_timesheets($staff_id,$year,$month),
            'late-submitted'            => $this->get_employees_late_submitted_timesheets($staff_id,$year,$month),
            'ontime-submitted'          => $this->get_employees_ontime_submitted_timesheets($staff_id,$year,$month),
            'waiting-spv-approval'      => $this->get_partially_submitted_timesheets('waiting-spv-approval', $staff_id,$year,$month),
            'waiting-hrm-approval'      => $this->get_partially_submitted_timesheets('waiting-hrm-approval', $staff_id,$year,$month),
            'in-drafts'                 => $this->get_partially_submitted_timesheets('in-drafts', $staff_id,$year,$month),
            'returned-for-correction'   => $this->get_partially_submitted_timesheets('returned-for-correction', $staff_id,$year,$month),
            'rejected'                  => $this->get_partially_submitted_timesheets('rejected', $staff_id,$year,$month),
            'not-filled-at-all'         => $this->get_employees_not_filled_timesheets_at_all('rejected', $staff_id,$year,$month),
        ];


        //dd($overview);
        
        return view('time_sheet_reports.overview_report',
            compact('report_title','overview','staff_name','year','month','generated_by',
                'year_filter', 'month_filter',  'employee_filter',
                'time_sheet_statuses','generation_date'));


    }


    public function generate_detailed_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "SUBMITTED TIME SHEETS REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;

        $months = TimeSheet::$months;
        $timeSheetSupervisors =  Staff::get_supervisors_list();

        $query = TimeSheet::query();
        //$query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        //$query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month','time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

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


        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //moth filter
        if( $month == 'all' ){

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }

        $time_sheets = $query->get();

        $statements = $this->getTimeSheetStatementsData($time_sheets);

        //dd($statements);

        return view('time_sheet_reports.detailed_report',
            compact('report_title','statements', 'timeSheetSupervisors', 'time_sheet_statuses', 'months',
                'staff_name','type_of_time_sheet','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter',
                'time_sheet_types','generation_date'));

    }



    public function generate_employees_submitted_timesheets_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "SUBMITTED & APPROVED TIME SHEETS REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //moth filter
        if( $month == 'all' ){

        }else{

        }


        $time_sheets = $this->get_submitted_timesheets($staff_id,$year,$month);

        //dd($time_sheets);

        return view('time_sheet_reports.submitted_timesheets_report',
            compact('report_title','time_sheets','staff_name',
            // 'type_of_time_sheet', 'from_date_filter','to_date_filter', 'time_sheet_type_filter','time_sheet_types'
            'year','month','generated_by',
                'year_filter', 'month_filter', 'employee_filter',
                'time_sheet_statuses','generation_date'));


    }

    public function get_submitted_timesheets($staff_id,$year,$month){

        $query = TimeSheet::query();
        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month','time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //moth filter
        if( $month == 'all' ){

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        //dd($time_sheets);

        return $time_sheets;

    }



    public function generate_employees_submitted_timesheets_with_loe_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "SUBMITTED TIME SHEETS REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;

        $months = TimeSheet::$months;
        $timeSheetSupervisors =  Staff::get_supervisors_list();

        $query = TimeSheet::query();
        //$query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        //$query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month','time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //moth filter
        if( $month == 'all' ){
            $month = date('n')-1;
            $query = $query->where('time_sheets.month','=',$month);
        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        $statements = $this->getTimeSheetStatementsData($time_sheets);

        //dd($statements);

        return view('time_sheet_reports.submitted_timesheets_with_loe_report',
            compact('report_title','statements', 'timeSheetSupervisors', 'time_sheet_statuses', 'months',
                'staff_name','type_of_time_sheet','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter',
                'time_sheet_types','generation_date'));

    }



    public function generate_employees_not_submitted_timesheets_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "EMPLOYEES WHO HAVE NOT SUBMITTED TIMESHEETS";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //month filter
        if( $month == 'all' ){

        }else{
        }



        $staff_who_have_not_submitted_timesheets = $this->get_employees_not_submitted_timesheets($staff_id,$year,$month);


        return view('time_sheet_reports.not_submitted_timesheets_report',
            compact('report_title','staff_name','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'time_sheet_type_filter',
                'generation_date', 'staff_who_have_not_submitted_timesheets'));


    }

    public function get_employees_not_submitted_timesheets($staff_id,$year,$month){


        $query = TimeSheet::query();
        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month',
            'time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at',
            'staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','>=','20');
        $query = $query->where('time_sheets.status','<>','99');



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }


        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //month filter
        if( $month == 'all' ){
            $query = $query->where('time_sheets.month','=',date('n'));

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        $all_staff = Staff::get_valid_staff_list();
        $staff_who_have_submitted_timesheets = [];
        $staff_who_have_not_submitted_timesheets = [];

        foreach ($time_sheets as $time_sheet){
            $staff_who_have_submitted_timesheets[] = $time_sheet->staff_id;
        }


        foreach ($all_staff as $staff){
            if(!in_array($staff->id,$staff_who_have_submitted_timesheets)){
                $staff_who_have_not_submitted_timesheets[] = $staff;
                //dd($staff);
            }
        }


        return $staff_who_have_not_submitted_timesheets;


    }



    public function generate_employees_late_submitted_timesheets_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "LATE TIMESHEETS SUBMISSION REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //month filter
        if( $month == 'all' ){

        }else{
        }



        $time_sheets = $this->get_employees_late_submitted_timesheets($staff_id,$year,$month);

        return view('time_sheet_reports.submitted_timesheets_report',
            compact('report_title','time_sheets', 'staff_name','type_of_time_sheet','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'time_sheet_type_filter',
                'time_sheet_statuses','time_sheet_types','generation_date'));

    }

    public function get_employees_late_submitted_timesheets($staff_id,$year,$month){

        //applied filters

        $query = TimeSheet::query();

        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month',
            'time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at',
            'staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }


        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //month filter
        if( $month == 'all' ){
            $query = $query->where('time_sheets.month','=',date('n'));

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();
        $time_sheets_submitted_late = [];


        foreach ($time_sheets as $time_sheet){
            if( count($time_sheet->late_submissions)> 0 ){
                $time_sheets_submitted_late[]= $time_sheet;
            }
        }


        return $time_sheets_submitted_late;

    }



    public function generate_employees_ontime_submitted_timesheets_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "TIMESHEETS SUBMITTED ON TIME";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;



        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //month filter
        if( $month == 'all' ){

        }else{
        }




        $time_sheets = $this->get_employees_ontime_submitted_timesheets($staff_id,$year,$month);

        return view('time_sheet_reports.submitted_timesheets_report',
            compact('report_title','time_sheets', 'staff_name','type_of_time_sheet','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'time_sheet_type_filter',
                'time_sheet_statuses','time_sheet_types','generation_date'));

    }

    public function get_employees_ontime_submitted_timesheets($staff_id,$year,$month){

        $query = TimeSheet::query();

        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month',
            'time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at',
            'staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //month filter
        if( $month == 'all' ){
            $query = $query->where('time_sheets.month','=',date('n'));

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();
        $time_sheets_submitted_ontime = [];


        foreach ($time_sheets as $time_sheet){
            if( count($time_sheet->late_submissions) == 0 ){
                $time_sheets_submitted_ontime[]= $time_sheet;
            }
        }

        return $time_sheets_submitted_ontime;

    }



    public function generate_partially_submitted_timesheets_report($report_type, $staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "";
        $time_sheet_status = '';

        switch ($report_type){
            case 'waiting-spv-approval' :
                $report_title = "TIMESHEETS WHICH ARE STILL WAITING FOR SPV APPROVAL";
                break;
            case 'waiting-hrm-approval' :
                $report_title = "TIMESHEETS WHICH ARE STILL WAITING FOR HRM APPROVAL";
                break;
            case 'in-drafts' :
                $report_title = "TIMESHEETS WHICH ARE NOT SUBMITTED BUT THEY ARE SAVED IN DRAFTS";
                break;
            case 'returned-for-correction' :
                $report_title = "TIMESHEETS WHICH HAVE BEEN RETURNED FOR CORRECTION AFTER SUBMISSION";
                break;
            case 'rejected' :
                $report_title = "TIMESHEETS WHICH HAVE BEEN REJECTED";
                break;
            default : $report_title = "";
        }


        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;



        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //month filter
        if( $month == 'all' ){

        }else{
        }



        $time_sheets = $this->get_partially_submitted_timesheets($report_type, $staff_id,$year,$month);

        return view('time_sheet_reports.submitted_timesheets_report',
            compact('report_title','time_sheets', 'staff_name',
            // 'type_of_time_sheet', 'from_date_filter','to_date_filter', 'time_sheet_type_filter','time_sheet_types',
            'year','month','generated_by',
                'year_filter', 'month_filter', 'employee_filter',
                'time_sheet_statuses','generation_date'));

    }

    public function get_partially_submitted_timesheets($report_type, $staff_id,$year,$month){

        switch ($report_type){
            case 'waiting-spv-approval' :
                $time_sheet_status = '20';
                break;
            case 'waiting-hrm-approval' :
                $time_sheet_status = '30';
                break;
            case 'in-drafts' :
                $time_sheet_status = '10';
                break;
            case 'returned-for-correction' :
                $time_sheet_status = '0';
                break;
            case 'rejected' :
                $time_sheet_status = '99';
                break;
            default : $time_sheet_status = "";
        }


        $query = TimeSheet::query();

        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month',
            'time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at',
            'staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',$time_sheet_status);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //month filter
        if( $month == 'all' ){
            //$query = $query->where('time_sheets.month','=',date('n'));

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        return $time_sheets;

    }



    public function generate_employees_not_filled_timesheets_at_all_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "EMPLOYEES WHO HAVE NOT FILLED TIMESHEETS AT ALL";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        //staff id filter
        if ( is_numeric($staff_id)) {
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
            }
        }

        //year filter
        if( $year == null ){
            $year = date('Y');
        }else{
        }

        //month filter
        if( $month == 'all' ){

        }else{
        }



        $staff_who_have_not_submitted_timesheets = $this->get_employees_not_filled_timesheets_at_all($staff_id,$year,$month);


        return view('time_sheet_reports.not_submitted_timesheets_report',
            compact('report_title','staff_name','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter', 'time_sheet_type_filter',
                'generation_date', 'staff_who_have_not_submitted_timesheets'));


    }

    public function get_employees_not_filled_timesheets_at_all($staff_id,$year,$month){


        $query = TimeSheet::query();
        $query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        $query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month',
            'time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at',
            'staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','>=','0');



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }


        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //month filter
        if( $month == 'all' ){
            $query = $query->where('time_sheets.month','=',date('n'));

        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        $all_staff = Staff::get_valid_staff_list();
        $staff_who_have_filled_timesheets = [];
        $staff_who_have_not_filled_timesheets_at_all = [];

        foreach ($time_sheets as $time_sheet){
            $staff_who_have_filled_timesheets[] = $time_sheet->staff_id;
        }


        foreach ($all_staff as $staff){
            if(!in_array($staff->id,$staff_who_have_filled_timesheets)){
                $staff_who_have_not_filled_timesheets_at_all[] = $staff;
                //dd($staff);
            }
        }


        return $staff_who_have_not_filled_timesheets_at_all;


    }



    public function getTimeSheetStatementsData($time_sheets)
    {

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $supervisors_mode = $system_settings->supervisors_mode;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_user = auth()->user();
        $current_logged_staff = $current_logged_user->staff;
        $user_role = $current_logged_user->role_id;

        $statements = [];


        foreach ($time_sheets as $time_sheet) {

            //dd($time_sheet_lines);
            //$time_sheet = TimeSheet::find($time_sheet->id);

            $time_sheet_lines = [];
            if($time_sheet_data_format == 1){//Normal Database Entries
                $time_sheet_lines = $time_sheet->lines;
                if( count($time_sheet_lines)>0){
                    $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
                }
            }
            elseif($time_sheet_data_format == 2 && isset($time_sheet->json_lines->data)){//JSON Format
                $time_sheet_lines = $time_sheet->json_lines->data;
                $time_sheet_lines = json_decode($time_sheet_lines,true);
            }

            $supervisor_id = $time_sheet->responsible_spv;
            //override supervisor settings if no supervisor have been assigned to this staff
            if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


            $responsible_spv = $time_sheet->responsible_spv;
            $supervisor = Staff::find($responsible_spv);
            $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

            //dd($timeSheetSupervisors);
            $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

            $project = new Project();
            $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);

            $holidays = Holiday::get_all_holidays_in_a_year($time_sheet->year)['arrays'];

            $employee_name = ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name);


            //get all dates which staff was on leave if there is any
            $staff_annual_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('annual_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
            $staff_sick_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('sick_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
            $staff_maternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('maternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
            $staff_paternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('paternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
            $staff_compassionate_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('compassionate_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
            $staff_other_off_dates = Leave::get_all_dates_of_staff_leave_in_a_month('other',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);


            $model_name = 'time_sheet';
            $controller_name = 'time_sheets';
            $view_type = 'show';

            $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
            $time_sheet_modification_reason = '';
            $supervisor_change_reason = '';
            $comments = '';
            //dd($time_sheet);



            $generated_by = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);
            $generation_date = date('d-m-Y H:m A');

            $spv = new Staff();
            $spv_approval = TimeSheetApproval::where('time_sheet_id','=',$time_sheet->id)->where('level','=','spv')->first();
            $spv_approval_date = '';

            $hrm = new Staff();
            $hrm_approval = TimeSheetApproval::where('time_sheet_id','=',$time_sheet->id)->where('level','=','hrm')->first();
            $hrm_approval_date = '';

            $md = new Staff();
            $md_approval = TimeSheetApproval::where('time_sheet_id','=',$time_sheet->id)->where('level','=','md')->first();
            $md_approval_date = '';

            if($time_sheet->status == 50){
                if( isset($spv_approval->done_by) ){ $spv = Staff::find($spv_approval->done_by); $spv_approval_date = $spv_approval->updated_at;}
                if( isset($hrm_approval->done_by) ){ $hrm = Staff::find($hrm_approval->done_by); $hrm_approval_date = $hrm_approval->updated_at;}
                if( isset($md_approval->done_by)  ){ $md = Staff::find($md_approval->done_by); $md_approval_date = $md_approval->updated_at;}
            }

            //dd($spv_approval);

            $statements[] = [
                'time_sheet' => $time_sheet,
                'responsible_spv' => $responsible_spv,
                'spv_name' => $spv_name,
                'employee_name' => $employee_name,
                'time_sheet_lines' => $time_sheet_lines,
                'holidays' => $holidays,
                'user_role' => $user_role,
                'leave_timesheet_link_mode' => $leave_timesheet_link_mode,
                'staff_annual_leave_dates' => $staff_annual_leave_dates,
                'staff_sick_leave_dates' => $staff_sick_leave_dates,
                'staff_maternity_leave_dates' => $staff_maternity_leave_dates,
                'staff_paternity_leave_dates' => $staff_paternity_leave_dates,
                'staff_compassionate_leave_dates' => $staff_compassionate_leave_dates,
                'staff_other_off_dates' => $staff_other_off_dates,
                'supervisors_mode' => $supervisors_mode,
                'days_in_month' => $days_in_month,
                'projects' => $projects,
                'rejection_reason' => $rejection_reason,
                'time_sheet_modification_reason' => $time_sheet_modification_reason,
                'supervisor_change_reason' => $supervisor_change_reason,
                'comments' => $comments,
                'generated_by' => $generated_by,
                'generation_date' => $generation_date,
                'md' => $md,
                'md_approval' => $md_approval,
                'md_approval_date' => $md_approval_date,
                'spv' => $spv,
                'spv_approval' => $spv_approval,
                'spv_approval_date' => $spv_approval_date,
                'hrm' => $hrm,
                'hrm_approval' => $hrm_approval,
                'hrm_approval_date' => $hrm_approval_date
            ];


        }


        return $statements;


    }


    public function convert_time_sheet_lines_to_array($lines){

        $lines_array = [];

        foreach ($lines as $line){

            $type = $line->type;
            $type_no = $line->type_no;
            $day = $line->day;
            $total_hrs = $line->total_hours;

            $line_name = $type.'--'.$type_no.'--'.$day;
            $lines_array[$line_name] = $total_hrs;
        }

        return $lines_array;

    }

    public function generate_projects_hrs_ratio_report($staff_id,$year,$month){

        //applied filters
        $year_filter = 'yes';
        $month_filter = 'yes';
        $employee_filter = 'yes';
        $report_title = "SUBMITTED TIME SHEETS REPORT";

        $default_year = date('Y');
        $staff_name = '';
        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m');
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;

        $months = TimeSheet::$months;
        $timeSheetSupervisors =  Staff::get_supervisors_list();

        $query = TimeSheet::query();
        //$query = $query->join('staff','staff.id','=','time_sheets.staff_id');
        //$query = $query->select('time_sheets.id','time_sheets.year','time_sheets.month','time_sheets.responsible_spv','time_sheets.status','time_sheets.created_at','time_sheets.updated_at','staff.id as staff_id','staff.first_name','staff.last_name');
        $query = $query->where('time_sheets.status','=',50);



        //staff id filter
        if ( is_numeric($staff_id)) {
            $query = $query->where('time_sheets.staff_id','=',$staff_id);

            $staff = Staff::find($staff_id);
            $staff_name = $staff->staff_no.' '.ucwords($staff->first_name.' '.$staff->last_name);

        }else{

            if ( $staff_id == 'all') {
                //don't filter staffs

            }else{
                $staff_status = $staff_id;
                $query = $query->where('staff.staff_status','=',$staff_status);
            }
        }


        //year filter
        if( $year == null ){
            $year = date('Y');
            $query = $query->where('time_sheets.year','=',$year);
        }else{
            $query = $query->where('time_sheets.year','=',$year);
        }

        //moth filter
        if( $month == 'all' ){
            $month = date('n')-1;
            $query = $query->where('time_sheets.month','=',$month);
        }else{
            $query = $query->where('time_sheets.month','=',$month);
        }



        $time_sheets = $query->get();

        $statements = $this->getTimeSheetStatementsData($time_sheets);

        //dd($statements);

        return view('time_sheet_reports.projects_hrs_ratio_report',
            compact('report_title','statements', 'timeSheetSupervisors', 'time_sheet_statuses', 'months',
                'staff_name','type_of_time_sheet','year','month','generated_by',
                'year_filter', 'month_filter', 'from_date_filter','to_date_filter', 'employee_filter',
                'time_sheet_types','generation_date'));

    }



}
