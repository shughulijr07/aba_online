<?php

namespace App\Http\Controllers;

use App\Events\SendTimeSheetToBC130;
use App\Events\TimeSheetApprovedByHRMEvent;
use App\Events\TimeSheetApprovedByMDEvent;
use App\Events\TimeSheetApprovedBySupervisorEvent;
use App\Events\TimeSheetRejectedEvent;
use App\Events\TimeSheetReturnedEvent;
use App\Events\TimeSheetSubmittedEvent;
use App\Models\GeneralS1etting;
use App\Models\GeneralSetting;
use App\Models\Holiday;
use App\Models\JsonTimeSheetLine;
use App\Models\Leave;
use App\Models\MyFunctions;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\TimeSheet;
use App\Models\TimeSheetApproval;
use App\Models\TimeSheetChangedSupervisor;
use App\Models\TimesheetClient;
use App\Models\TimeSheetLateSubmission;
use App\Models\TimeSheetLine;
use App\Models\TimeSheetReject;
use App\Models\TimeSheetReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TimeSheetsController extends Controller
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
    public function index($status)
    {
        if (Gate::denies('access',['time_sheets','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;

        //for my time sheets
        $time_sheets = TimeSheet::where('status', '=', $status)->where('staff_id','=',$employee_id)->get();

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'index';


        $time_sheet_statuses = TimeSheet::$timesheet_statuses;
        $time_sheet_status   = $status;
        $time_sheet_months   = TimeSheet::$months;

        return view('time_sheets.index',
            compact('time_sheets', 'time_sheet_statuses', 'time_sheet_status', 'time_sheet_months',
                'model_name', 'controller_name', 'view_type'));
    }

    public function time_sheets()
    {
        if (Gate::denies('access',['time_sheets','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        $supv = Supervisor::where('staff_id', $employee_id)->first();
        // dd($supv);
        //for my time sheets

        if (Auth::user()->role_id == 1) {
            $time_sheets = DB::table('time_sheets')
                       ->join('staff', 'time_sheets.staff_id', '=', 'staff.id')
                       ->select('time_sheets.*', 'staff.first_name','staff.middle_name','staff.last_name')
                       ->get();
             } else {
                 $time_sheets = DB::table('time_sheets')
                       ->join('staff', 'time_sheets.staff_id', '=', 'staff.id')
                       ->select('time_sheets.*', 'staff.first_name','staff.middle_name','staff.last_name')
                        ->where('responsible_spv', $supv->staff_id)
                       ->get();
        }
        
                   
        return view('time_sheets.time-sheets')
        ->with('time_sheets', $time_sheets)
                    ;
    }


    public function adminIndex( $status )
    {
        if (Gate::denies('access',['time_sheets','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        $current_user_role = auth()->user()->role_id;

        $time_sheets = [];
        $months = TimeSheet::$months;

        if(in_array($current_user_role,[1,2,3])){ //for Super Administrator or HRM or MD
            $time_sheets = TimeSheet::where('status', '=', $status)->get();
        }

        if(in_array($current_user_role,[5,9])){ // for SPV
            $time_sheets = TimeSheet::where('status', '=', $status)->where('responsible_spv','=',$employee_id)->get();
        }

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'index';


        $time_sheet_statuses = TimeSheet::$timesheet_statuses;
        $time_sheet_status = $status;

        return view('time_sheets.admin_index',
            compact('time_sheets', 'time_sheet_statuses','time_sheet_status','months',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myTimeSheetsIndex()
    {

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'index';

        return view('my_time_sheets.index',
            compact(   'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myTimeSheetsList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff = auth()->user()->staff;
        $staff_id = $staff->id;
        $months = TimeSheet::$months;
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        $query = TimeSheet::query();
        $query = $query->where('staff_id', '=', $staff_id);
        $query = $query->where('status', '=', '50');//approved
        $query = $query->where('year', '=', $year);

        $time_sheets = $query->get();

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'index';

        return view('my_time_sheets.list',
            compact(  'time_sheets',  'year','months','time_sheet_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function create()
    {
        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }

        //detect late submissions and lock them
        TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $current_logged_staff = auth()->user()->staff;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $current_logged_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $timeSheetSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $year = date('Y');
        $months = TimeSheet::$months;
        $current_month = date('m');
        $initial_year = --$year;

        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'create';

        return view('time_sheets.create',
            compact( 'timeSheetSupervisors', 'responsible_spv', 'employee_name',
                'supervisors_mode','year','months','current_month','initial_year',
                'model_name', 'controller_name', 'view_type'));



    }


    // public function createForAnotherStaff()
    // {
    //     if (Gate::denies('access',['time_sheets','store'])){
    //         abort(403, 'Access Denied');
    //     }

    //     //detect late submissions and lock them
    //     //TimeSheetLateSubmission::lock_late_time_sheet_submissions();

    //     $current_logged_staff = auth()->user()->staff;

    //     //get system settings
    //     $system_settings = GeneralSetting::find(1);
    //     $supervisors_mode = $system_settings->supervisors_mode;
    //     $supervisor_id = $current_logged_staff->supervisor_id;
    //     //override supervisor settings if no supervisor have been assigned to this staff
    //     if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


    //     $timeSheetSupervisors = Staff::get_supervisors('2');
    //     $employees = Staff::get_valid_staff_list();
    //     $responsible_spv = '';
    //     $year = date('Y');
    //     $months = TimeSheet::$months;
    //     $current_month = date('m');
    //     $initial_year = --$year;

    //     $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


    //     $model_name = 'time_sheet';
    //     $controller_name = 'time_sheets';
    //     $view_type = 'create';

    //     return view('time_sheets.create_for_another_staff',
    //         compact( 'timeSheetSupervisors', 'responsible_spv', 'employee_name', 'employees',
    //             'supervisors_mode','year','months','current_month','initial_year',
    //             'model_name', 'controller_name', 'view_type'));
    // }

     public function new_createForAnotherStaff()
    {

        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }
        //detect late submissions and lock them
        //TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $current_logged_staff = auth()->user()->staff;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        // $supervisor_id = $current_logged_staff->supervisor_id;
        // $super = Supervisor::find($time_sheet->responsible_spv);
        // $supervisor = Staff::find($super->staff_id);

        $my_staff_id = Supervisor::where('staff_id', $current_logged_staff->id)->first();
        
        $supervisor_id = $my_staff_id->staff_id;
        
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $timeSheetSupervisors = Staff::get_supervisors('2');
        // if (session('role' == 1)) {
        if (Auth::user()->role_id == 1){
            $employees = Staff::all();
        } else {
            $employees = Staff::new_get_valid_staff_list($supervisor_id);
        }
   
        $responsible_spv = '';
        $year = date('Y');
        $months = TimeSheet::$months;
        $current_month = date('m');
        $initial_year = --$year;

        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'create';

        return view('time_sheets.emp-timesheet-task',
            compact( 'timeSheetSupervisors', 'responsible_spv', 'employee_name', 'employees','my_staff_id',
                'supervisors_mode','year','months','current_month','initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function createTimeSheetLines($time_sheet_id)
    {
        //sio hii
        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }

        //detect late submissions and lock them
        TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_staff = auth()->user()->staff;
        $time_sheet = TimeSheet::find($time_sheet_id);



        $year = $time_sheet->year; $month =$time_sheet->month;

        $months = TimeSheet::$months;

        //check if submission month  have been locked
        if($month < date('n')){
            $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
            //dd($feedback);

            if( $feedback['status'] == 'not-allowed'){
                return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
            }

        }
        elseif($month > date('n')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year.' yet';

            return redirect('new_time_sheet')->with('message', $message);

        }elseif( $year != date('Y')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year;

            return redirect('new_time_sheet')->with('message', $message);

        }




        $time_sheet_lines = [];
        if($time_sheet_data_format == 1){//Normal Database Entries
            $time_sheet_lines = $time_sheet->lines;
            if( count($time_sheet_lines)>0){
                $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
            }
        }
        elseif($time_sheet_data_format == 2){//JSON Format
            if( isset($time_sheet->json_lines)){
                $time_sheet_lines = $time_sheet->json_lines->data;
                $time_sheet_lines = json_decode($time_sheet_lines,true);
            }
        }

        $supervisor_id = $current_logged_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $timeSheetSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $months = TimeSheet::$months;
        $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);

        $holidays = Holiday::get_all_holidays_in_a_year($time_sheet->year)['arrays'];



        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


        //get all dates which staff was on leave if there is any
        $staff_annual_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('annual_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_sick_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('sick_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_maternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('maternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_paternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('paternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_compassionate_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('compassionate_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_other_off_dates = Leave::get_all_dates_of_staff_leave_in_a_month('other',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);


        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'create';

        return view('time_sheets.create_entries',
            compact( 'time_sheet','time_sheet_lines', 'timeSheetSupervisors', 'responsible_spv', 'employee_name',
                'supervisors_mode','months','days_in_month', 'projects','holidays','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'model_name', 'controller_name', 'view_type'));

    }


    public function createTimeSheetLinesAdmin($time_sheet_id)
    {

        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }


        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_staff = auth()->user()->staff;
        $time_sheet = TimeSheet::find($time_sheet_id);


        $time_sheet_lines = [];
        if($time_sheet_data_format == 1){//Normal Database Entries
            $time_sheet_lines = $time_sheet->lines;
            if( count($time_sheet_lines)>0){
                $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
            }
        }
        elseif($time_sheet_data_format == 2){//JSON Format
            if( isset($time_sheet->json_lines)){
                $time_sheet_lines = $time_sheet->json_lines->data;
                $time_sheet_lines = json_decode($time_sheet_lines,true);
            }
        }

        $supervisor = Staff::find($time_sheet->responsible_spv);
        $supervisor_id = $time_sheet->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $supervisors_mode = '2';
        $timeSheetSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = $time_sheet->responsible_spv;;
        $months = TimeSheet::$months;
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
        $view_type = 'create';

        return view('time_sheets.create_entries_admin',
            compact( 'time_sheet','time_sheet_lines', 'timeSheetSupervisors', 'responsible_spv', 'employee_name',
                'supervisors_mode','months','days_in_month', 'projects','holidays','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'model_name', 'controller_name', 'view_type'));

    }


    public function store(Request $request)
    {

        //detect late submissions and lock them
        TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $data = $request->all();

        //get timesheet header data
        $employee_name = $data['employee_name'];
        $responsible_spv = $data['responsible_spv'];
        $year = $data['year'];
        $month = $data['month'];
        $months = TimeSheet::$months;

        $staff_id = auth()->user()->staff->id;

        //check if timesheet submission deadline has passed

        $months = TimeSheet::$months;

        #This section is for restricting late timesheet submission and timesheet which are submitted before or after current month
        //check if submission month  have been locked
        /*
        if($month < date('n')){
            $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
            //dd($feedback);

            if( $feedback['status'] == 'not-allowed'){
                return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
            }

        }
        elseif($month > date('n')){

            $message = 'You can not create time sheet the month of '.$months[$month].', '.$year.' yet';

            return redirect('new_time_sheet')->with('message', $message);

        }elseif( $year != date('Y')){

            $message = 'You can not create time sheet the month of '.$months[$month].', '.$year;

            return redirect('new_time_sheet')->with('message', $message);

        }
        */
        #end of section for submission restriction


        //check if timesheet for this date have been submitted before
        $time_sheet = TimeSheet::get_timesheet_by_date($staff_id, $year,$month);

        if($time_sheet == null){

            //save time sheet (time sheet header)
            $time_sheet  = new TimeSheet();
            $time_sheet->staff_id = $staff_id;
            $time_sheet->year = $year;
            $time_sheet->month = $month;
            $time_sheet->responsible_spv = $responsible_spv;
            $time_sheet->status = '10'; //add to drafts
            $time_sheet->transferred_to_nav = 'no';
            $time_sheet->save();

            return redirect('create_time_sheet_entries/'.$time_sheet->id);

        }else{
            $message = 'You Have Already Created Time Sheet for the month of '.$months[$month];

            return redirect('new_time_sheet')->with('message', $message);

        }



    }

  public function timesheet_add_client(Request $request){
        if ($request->has('mode')) {
            if ($request->mode == "addclient") {
                $data = request()->validate([
                    'time_sheet_id' => 'required',
                    'project_id' => 'required',
                    'time_sheet_id' => 'required'
                ]);
        
                TimesheetClient::create([
                    'time_sheet_id' => $data['time_sheet_id'],
                    'project_id' => $data['project_id'],
                ]);
            }
            if ($request->mode == "addtask") {
                $data = request()->validate([
                    'timesheet_client_id' => 'required',
                    'task_name' => 'required',
                    'time_sheet_id' => 'required',
                   
                ]);
        
                Task::create([
                    'timesheet_client_id' => $data['timesheet_client_id'],
                    'task_name' => $data['task_name'],
                ]);
            }
            return redirect()->to('assign_client_task/'.$data['time_sheet_id']);
        }
       return redirect()->back();
    }

    public function delete_task($id){
        // if (Gate::denies('access',['time_sheets','view'])){
        //     abort(403, 'Access Denied');
        // }
      $delete_data = Task::where('id','=',$id)->delete();
       if($delete_data){
          return redirect()->back();
       }
          return redirect()->back();

    }

    public function delete_draft_timesheets($id){
        // if (Gate::denies('access',['time_sheets','view'])){
        //     abort(403, 'Access Denied');
        // }

       $delete_data = Timesheet::where('id','=',$id)->delete();
       if($delete_data){
          return redirect()->back();
       }
          return redirect()->back();
    }

    public function assign_client_task($id){
        $clients = Project::all();
        $time_sheet = TimeSheet::find($id);
        if ($time_sheet) {
            // $super = Supervisor::find($time_sheet->responsible_spv);
            //  dd($time_sheet);

            $supervisor = Staff::find($time_sheet->responsible_spv);

        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);
        $employee_name = ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name);
        $clientsheets = TimesheetClient::where('time_sheet_id', $time_sheet->id)->get();
        return view('time_sheets.assign_client_task')
                 ->with('employee_name', $employee_name)
                 ->with('time_sheet', $time_sheet)
                 ->with('clients', $clients)
                 ->with('clientsheets', $clientsheets)
                 ->with('spv_name', $spv_name);
        }

        return redirect()->back();
        
    }

     public function new_storeForAnotherStaff(Request $request)
    {
        $data = request()->validate([
            'staff_id' => 'required',
            // 'responsible_spv' =>  'nullable|required',
            'year' =>  'required',
            'month' =>  'required',
        ]);

        //get timesheet header data
        $staff_id = $data['staff_id'];

        if (Auth::user()->role_id == 1) {
            $responsible_spv =$request->responsible_spv;
        } else {
            $supv = Staff::find($staff_id);
            $responsible_spv = $supv->supervisor_id;
        }

        $year = $data['year'];
        $month = $data['month'];
        $months = TimeSheet::$months;


        //check if timesheet for this date have been submitted before
        $time_sheet = TimeSheet::get_timesheet_by_date($staff_id, $year,$month);

        if($time_sheet == null){

            //save time sheet (time sheet header)
            $time_sheet  = new TimeSheet();
            $time_sheet->staff_id = $staff_id;
            $time_sheet->year = $year;
            $time_sheet->month = $month;
            $time_sheet->responsible_spv = $responsible_spv;
            $time_sheet->status = '10'; //it goes to spv direct as submitted because it was not created by staff directly
            $time_sheet->transferred_to_nav = 'no';
            $time_sheet->save();

            return redirect('assign_client_task/'.$time_sheet->id);

        }else{
            $message = 'You Have Already Created Time Sheet for the month of '.$months[$month];

            return redirect('create_timesheet_for_another_staff')->with('message', $message);

        }
    }

    public function storeForAnotherStaff(Request $request)
    {

        $data = request()->validate([
            'staff_id' => 'required',
            'responsible_spv' =>  'required',
            'year' =>  'required',
            'month' =>  'required',
        ]);



        //get timesheet header data
        $staff_id = $data['staff_id'];
        $responsible_spv = $data['responsible_spv'];
        $year = $data['year'];
        $month = $data['month'];
        $months = TimeSheet::$months;


        //check if timesheet for this date have been submitted before
        $time_sheet = TimeSheet::get_timesheet_by_date($staff_id, $year,$month);
//        dump($staff_id);
//        dump($time_sheet);
//        dump($responsible_spv);
//        dd($month);

        if($time_sheet == null){

            //save time sheet (time sheet header)
            $time_sheet  = new TimeSheet();
            $time_sheet->staff_id = $staff_id;
            $time_sheet->year = $year;
            $time_sheet->month = $month;
            $time_sheet->responsible_spv = $responsible_spv;
            $time_sheet->status = '10'; //it goes to spv direct as submitted because it was not created by staff directly
            $time_sheet->transferred_to_nav = 'no';
            $time_sheet->save();

            return redirect('create_time_sheet_entries_admin/'.$time_sheet->id);

        }else{
            $message = 'You Have Already Created Time Sheet for the month of '.$months[$month];

            return redirect('create_timesheet_for_another_staff')->with('message', $message);

        }



    }


    public function storeTimesheetData(Request $request)
    {
        dd( $request->all() );
        //detect late submissions and lock them
        TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $data = $request->all();
        // dd($data);

        //get timesheet header data
        $time_sheet_id = $data['time_sheet_id'];
        $employee_name = $data['employee_name'];
        $responsible_spv = $data['responsible_spv'];
        $year = $data['year'];
        $month = $data['month'];
        $action = $data['action'];  //dd('ni hii hii');
        $staff_id = auth()->user()->staff->id;

        //get timesheet lines
        $lines = array_slice($data, 7);

        //dd($lines);

        //change time sheet status according to action
        $time_sheet = TimeSheet::find($time_sheet_id);


        $months = TimeSheet::$months;

        //check if submission month  have been locked
        /*
        if($month < date('n')){
            $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
            //dd($feedback);

            if( $feedback['status'] == 'not-allowed'){
                return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
            }

        }
        elseif($month > date('n')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year.' yet';

            return redirect('new_time_sheet')->with('message', $message);

        }elseif( $year != date('Y')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year;

            return redirect('new_time_sheet')->with('message', $message);

        }
        */
        //get system settings and check what format have been chosen for saving time sheet data(lines)
        $system_settings = GeneralSetting::find(1);
        $time_sheet_data_format = $system_settings->time_sheet_data_format;

        if($time_sheet_data_format == 1){//Normal Database Entries
            //remove old time sheet lines
            TimeSheetLine::where('time_sheet_id','=',$time_sheet_id)->delete();

            //save new time sheet lines
            foreach ($lines as $line_name => $total_hrs ){

                $line_data = explode('--', $line_name);

                if( count($line_data) >= 3){

                    $time_sheet_line = new TimeSheetLine();
                    $time_sheet_line->time_sheet_id = $time_sheet_id;
                    $time_sheet_line->type = $line_data[0];
                    $time_sheet_line->type_no = $line_data[1];
                    $time_sheet_line->day = $line_data[2];
                    $time_sheet_line->total_hours = $total_hrs;
                    $time_sheet_line->save();

                }
            }
        }

        elseif($time_sheet_data_format == 2){//JSON Format

            //replace time sheet data
            $json_time_sheet_line = JsonTimeSheetLine::where('time_sheet_id','=',$time_sheet_id)->first();

            if(isset($json_time_sheet_line->id)){

                $json_time_sheet_line->data = json_encode($lines);
                $json_time_sheet_line->save();

            }else{
                //save new time sheet lines
                $json_time_sheet_line = new JsonTimeSheetLine();
                $json_time_sheet_line->time_sheet_id = $time_sheet_id;
                $json_time_sheet_line->data = json_encode($lines);
                $json_time_sheet_line->save();
            }

        }


        if( $data['whichBtn'] == 'Submit Time Sheet' ){

            $time_sheet->status = '20'; //'20' => 'Waiting For Supervisor Approval'
            $time_sheet->save();

            //send notifications  -- the event have errors
            // if( isset($time_sheet->id) ){
            //     //dump('inatuma email');
            //     event( new TimeSheetSubmittedEvent($time_sheet));
            // }

            return redirect('time_sheets/20');

        }else{

            $time_sheet->status = '10'; //'10' => 'Draft'
            $time_sheet->save();

            return redirect('time_sheets/10');
        }


    }


    public function storeTimesheetDataAdmin(Request $request)
    {

        //detect late submissions and lock them
        //TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $data = $request->all();
        //dd($data);

        //get timesheet header data
        $time_sheet_id = $data['time_sheet_id'];
        $employee_name = $data['employee_name'];
        $responsible_spv = $data['responsible_spv'];
        $year = $data['year'];
        $month = $data['month'];
        $action = $data['action'];  //dd('ni hii hii');
        $staff_id = auth()->user()->staff->id;

        //get timesheet lines
        $lines = array_slice($data, 7);

        //dd($lines);

        //change time sheet status according to action
        $time_sheet = TimeSheet::find($time_sheet_id);


        $months = TimeSheet::$months;


        //get system settings and check what format have been chosen for saving time sheet data(lines)
        $system_settings = GeneralSetting::find(1);
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        if($time_sheet_data_format == 1){//Normal Database Entries

            //remove old time sheet lines
            TimeSheetLine::where('time_sheet_id','=',$time_sheet_id)->delete();

            //save new time sheet lines
            foreach ($lines as $line_name => $total_hrs ){

                $line_data = explode('--', $line_name);

                if( count($line_data) >= 3){

                    $time_sheet_line = new TimeSheetLine();
                    $time_sheet_line->time_sheet_id = $time_sheet_id;
                    $time_sheet_line->type = $line_data[0];
                    $time_sheet_line->type_no = $line_data[1];
                    $time_sheet_line->day = $line_data[2];
                    $time_sheet_line->total_hours = $total_hrs;
                    $time_sheet_line->save();

                }

            }



        }

        elseif($time_sheet_data_format == 2){//JSON Format

            //replace time sheet data
            $json_time_sheet_line = JsonTimeSheetLine::where('time_sheet_id','=',$time_sheet_id)->first();

            if(isset($json_time_sheet_line->id)){

                $json_time_sheet_line->data = json_encode($lines);
                $json_time_sheet_line->save();

            }else{
                //save new time sheet lines
                $json_time_sheet_line = new JsonTimeSheetLine();
                $json_time_sheet_line->time_sheet_id = $time_sheet_id;
                $json_time_sheet_line->data = json_encode($lines);
                $json_time_sheet_line->save();
            }

        }

        //edit timesheet supervisor
        $time_sheet->responsible_spv = $responsible_spv;
        $time_sheet->save();



        //dd('pause');

        return redirect('admin_time_sheets/'.$time_sheet->status);


    }


    public function show($id)
    {

        if (Gate::denies('access',['time_sheets','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $supervisors_mode = $system_settings->supervisors_mode;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_staff = auth()->user()->staff;

        //dd($time_sheet_lines);
        $time_sheet = TimeSheet::find($id);

        $time_sheet_lines = [];
        if($time_sheet_data_format == 1){//Normal Database Entries
            $time_sheet_lines = $time_sheet->lines;
            if( count($time_sheet_lines)>0){
                $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
            }
        }
        elseif($time_sheet_data_format == 2){//JSON Format
            $time_sheet_lines = $time_sheet->json_lines->data;
            $time_sheet_lines = json_decode($time_sheet_lines,true);
        }

        //dd($time_sheet_lines);

        //dd($time_sheet_lines);
        $supervisor_id = $time_sheet->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $timeSheetSupervisors =  Staff::get_supervisors($supervisors_mode);
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;
        $responsible_spv = $time_sheet->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $months = TimeSheet::$months;
        $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

        // $project = new Project();
        // $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);

        $projects = TimesheetClient::find_client_timesheet($id);

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
        $my_role_id = Auth::user()->role_id;
        $my_staff_id = $current_logged_staff->id;

        // dd($time_sheet);

        return view('time_sheets.show',
            compact( 'time_sheet','timeSheetSupervisors', 'responsible_spv', 'spv_name', 'employee_name','my_staff_id',
                'time_sheet_lines','time_sheet_statuses','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'months', 'supervisors_mode','days_in_month','projects','holidays',
                'rejection_reason','time_sheet_modification_reason', 'supervisor_change_reason','comments',
                'model_name', 'controller_name', 'view_type'));
    }


    public function showAdmin($id)
    {
        if (Gate::denies('access',['time_sheets','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $supervisors_mode = $system_settings->supervisors_mode;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_user = auth()->user();
        $current_logged_staff = $current_logged_user->staff;
        $user_role = $current_logged_user->role_id;

        //dd($time_sheet_lines);
        $time_sheet = TimeSheet::find($id);

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


        $timeSheetSupervisors =  Staff::get_supervisors_list();
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;
        $responsible_spv = $time_sheet->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

        //dd($timeSheetSupervisors);

        $months = TimeSheet::$months;
        $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

        // $project = new Project();
        // $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);

        $projects = TimesheetClient::find_client_timesheet($id);


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
        $my_staff_id = $current_logged_staff->id;


        return view('time_sheets.show_admin',
            compact( 'time_sheet','timeSheetSupervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'time_sheet_lines','time_sheet_statuses','holidays','user_role','leave_timesheet_link_mode','my_staff_id',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'months', 'supervisors_mode','days_in_month','projects',
                'rejection_reason','time_sheet_modification_reason', 'supervisor_change_reason','comments',
                'model_name', 'controller_name', 'view_type'));
    }


    public function showTimeSheetStatement($id)
    {

        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $supervisors_mode = $system_settings->supervisors_mode;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $current_logged_user = auth()->user();
        $current_logged_staff = $current_logged_user->staff;
        $user_role = $current_logged_user->role_id;

        //dd($time_sheet_lines);
        $time_sheet = TimeSheet::find($id);

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


        $timeSheetSupervisors =  Staff::get_supervisors_list();
        $time_sheet_statuses = TimeSheet::$timesheet_statuses;
        $responsible_spv = $time_sheet->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

        //dd($timeSheetSupervisors);


        $months = TimeSheet::$months;
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
        $spv_approval = TimeSheetApproval::where('time_sheet_id','=',$id)->where('level','=','spv')->first();

        $hrm = new Staff();
        $hrm_approval = TimeSheetApproval::where('time_sheet_id','=',$id)->where('level','=','hrm')->first();

        $md = new Staff();
        $md_approval = TimeSheetApproval::where('time_sheet_id','=',$id)->where('level','=','md')->first();

        if($time_sheet->status == 50){
            if( isset($spv_approval->done_by) ){ $spv = Staff::find($spv_approval->done_by); }
            if( isset($hrm_approval->done_by) ){ $hrm = Staff::find($hrm_approval->done_by); }
            if( isset($md_approval->done_by)  ){ $md = Staff::find($md_approval->done_by); }
        }

        //dd($spv_approval);

        return view('time_sheets.statement',
            compact( 'time_sheet','timeSheetSupervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'time_sheet_lines','time_sheet_statuses','holidays','user_role','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'months', 'supervisors_mode','days_in_month','projects',
                'rejection_reason','time_sheet_modification_reason', 'supervisor_change_reason','comments',
                'generated_by','generation_date','md','md_approval', 'spv_approval','spv','hrm_approval', 'hrm',
                'model_name', 'controller_name', 'view_type'));


    }

     public function preview_timesheet(Request $request){
        $id = $request->sheet;
        $time_sheet = TimeSheet::find($id);
        $responsible_spv = $time_sheet->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);
        $employee_name = ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name);
        $clients = TimesheetClient::find_client_timesheet($id);
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $holidays = Holiday::get_all_holidays_in_a_year($time_sheet->year)['arrays'];
      return view('time_sheets.preview')
              ->with('holidays', $holidays)
              ->with('time_sheet', $time_sheet)
              ->with('responsible_spv', $responsible_spv)
              ->with('supervisors_mode', $supervisors_mode)
              ->with('spv_name', $spv_name)
              ->with('days_in_month', $days_in_month)
              ->with('clients', $clients)
              ->with('employee_name', $employee_name);
    }

    public function editTimeSheetData($time_sheet_id)
    {

        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }

        //detect late submissions and lock them
        //TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $current_logged_staff = auth()->user()->staff;
        // dd($current_logged_staff->id);

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;

        $supervisor_id = $current_logged_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $time_sheet = TimeSheet::find($time_sheet_id);

        // $super = Supervisor::find($time_sheet->responsible_spv);

        // $supervisor = Staff::find($super->id);
        $supervisor = Staff::find($time_sheet->responsible_spv);

        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

        $year = $time_sheet->year; $month =$time_sheet->month;

        $months = TimeSheet::$months;

        //check if submission month  have been locked
        /*
        if($month < date('n')){
            $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
            //dd($feedback);

            if( $feedback['status'] == 'not-allowed'){
                return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
            }

        }
        elseif($month > date('n')){

            $message = 'You can not create time sheet the month of '.$months[$month].', '.$year.' yet';

            return redirect('new_time_sheet')->with('message', $message);

        }elseif( $year != date('Y')){

            $message = 'You can not create time sheet the month of '.$months[$month].', '.$year;

            return redirect('new_time_sheet')->with('message', $message);

        }
        */




        $time_sheet_lines = [];
        if($time_sheet_data_format == 1){//Normal Database Entries
            $time_sheet_lines = $time_sheet->lines;

            if(count($time_sheet_lines) == 0){//no lines have been created
                return redirect('create_time_sheet_entries/'.$time_sheet_id);
            }else{// lines have already been created convert them to array
                $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
            }
        }
        elseif($time_sheet_data_format == 2){//JSON Format
            if( isset($time_sheet->json_lines) ){
                $time_sheet_lines = $time_sheet->json_lines->data;
                $time_sheet_lines = json_decode($time_sheet_lines,true);
            }
        }

        $time_sheet_statuses = TimeSheet::$timesheet_statuses;


        $timeSheetSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $months = TimeSheet::$months;
        $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

        // $project = new Project();
        // $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);

        //Added lines to search for clients instead of projects
        $projects = TimesheetClient::find_client_timesheet($time_sheet_id);
        // Added ends here



        $holidays = Holiday::get_all_holidays_in_a_year($time_sheet->year)['arrays'];

        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


        //get all dates which staff was on leave if there is any
        $staff_annual_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('annual_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_sick_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('sick_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_maternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('maternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_paternity_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('paternity_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_compassionate_leave_dates = Leave::get_all_dates_of_staff_leave_in_a_month('compassionate_leave',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);
        $staff_other_off_dates = Leave::get_all_dates_of_staff_leave_in_a_month('other',$time_sheet->staff->id, $time_sheet->year, $time_sheet->month);

        $responsible_spv = $supervisor->id;


        // dd($responsible_spv);

        $model_name = 'time_sheet';
        $controller_name = 'time_sheets';
        $view_type = 'edit';
        $my_staff_id = $current_logged_staff->id;
        // dd( $time_sheet_lines );
        return view('time_sheets.edit',
            compact( 'time_sheet','time_sheet_lines', 'timeSheetSupervisors', 'responsible_spv', 'employee_name','spv_name', 'my_staff_id',
                'supervisors_mode','months','days_in_month', 'projects','holidays','time_sheet_statuses','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates',
                'model_name', 'controller_name', 'view_type'));


    }


    public function adminEditTimeSheetData($time_sheet_id)
    {

        if (Gate::denies('access',['time_sheets','store'])){
            abort(403, 'Access Denied');
        }

        //detect late submissions and lock them
        //TimeSheetLateSubmission::lock_late_time_sheet_submissions();

        $current_logged_staff = auth()->user()->staff;
        $user_role = auth()->user()->role_id;



        //dd($time_sheet_lines);

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $leave_timesheet_link_mode = $system_settings->leave_timesheet_link;
        $time_sheet_data_format = $system_settings->time_sheet_data_format;


        $time_sheet = TimeSheet::find($time_sheet_id);

        $supervisor_id = $time_sheet->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'




        $year = $time_sheet->year; $month =$time_sheet->month;

        $months = TimeSheet::$months;

        /*
                //check if submission month  have been locked
                if($month < date('n')){
                    $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
                    //dd($feedback);

                    if( $feedback['status'] == 'not-allowed'){
                        return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
                    }

                }
                elseif($month > date('n')){

                    $message = 'You can not create time sheet the month of'.$months[$month].', '.$year.' yet';

                    return redirect('new_time_sheet')->with('message', $message);

                }elseif( $year != date('Y')){

                    $message = 'You can not create time sheet the month of'.$months[$month].', '.$year;

                    return redirect('new_time_sheet')->with('message', $message);

                }*/

        $time_sheet_lines = [];
        if($time_sheet_data_format == 1){//Normal Database Entries
            $time_sheet_lines = $time_sheet->lines;

            if(count($time_sheet_lines) == 0){//no lines have been created
                return redirect('create_time_sheet_entries/'.$time_sheet_id);
            }else{// lines have already been created convert them to array
                $time_sheet_lines = $this->convert_time_sheet_lines_to_array($time_sheet_lines);
            }
        }
        elseif($time_sheet_data_format == 2){//JSON Format
            if( isset($time_sheet->json_lines) ){
                $time_sheet_lines = $time_sheet->json_lines->data;
                $time_sheet_lines = json_decode($time_sheet_lines,true);
            }
        }

        $time_sheet_statuses = TimeSheet::$timesheet_statuses;

        $timeSheetSupervisors = Staff::get_supervisors('2');
        $responsible_spv = $time_sheet->responsible_spv;
        $months = TimeSheet::$months;
        $days_in_month = $number = cal_days_in_month(CAL_GREGORIAN, $time_sheet->month, $time_sheet->year);

        // $project = new Project();
        // $projects = $project->process_active_projects_for_a_month($time_sheet->year, $time_sheet->month);
        $projects = TimesheetClient::find_client_timesheet($time_sheet_id);
        $comments = '';
        $supervisor_change_reason = '';
        $rejection_reason = '';


        $holidays = Holiday::get_all_holidays_in_a_year($time_sheet->year)['arrays'];
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);
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
        $view_type = 'edit_admin';


        return view('time_sheets.edit_admin',
            compact( 'time_sheet','time_sheet_lines', 'timeSheetSupervisors', 'responsible_spv', 'employee_name', 'spv_name',
                'supervisors_mode','months','days_in_month', 'projects','holidays','time_sheet_statuses','leave_timesheet_link_mode',
                'staff_annual_leave_dates','staff_sick_leave_dates','staff_maternity_leave_dates','staff_paternity_leave_dates',
                'staff_compassionate_leave_dates','staff_other_off_dates','user_role', 'comments', 'supervisor_change_reason',
                'rejection_reason',
                'model_name', 'controller_name', 'view_type'));


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


    public function check_if_time_sheet_processing_is_locked($year,$month){

        $months = TimeSheet::$months;

        //check if submission month  have been locked
        if($month < date('n')){
            $feedback = TimeSheetLateSubmission::check_if_is_allowed_to_submit(auth()->user()->staff->id,$year,$month);
            //dd($feedback);

            if( $feedback['status'] == 'not-allowed'){
                return redirect('time_sheet_late_submissions/'.$feedback['id'].'/edit');
            }

        }
        elseif($month > date('n')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year.' yet';

            return redirect('new_time_sheet')->with('message', $message);

        }elseif( $year != date('Y')){

            $message = 'You can not create time sheet the month of'.$months[$month].', '.$year;

            return redirect('new_time_sheet')->with('message', $message);

        }



    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /************************ TIME SHEET APPROVING *******************/

    public function approveTimeSheet(Request $request){

        if (Gate::denies('access',['approve_time_sheet','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Time Sheet');
        }

        $data = $this->validateApprovalRequest();
        $time_sheet_id= $data['time_sheet_id'];
        $comments = $data['comments'];

        return $this->approveSubmittedTimesheet($time_sheet_id,$comments);

    }


    public function approveSubmittedTimesheet($time_sheet_id,$comments = ''){

        if (Gate::denies('access',['approve_time_sheet','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Time Sheet');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $time_sheet = TimeSheet::find($time_sheet_id);
        $time_sheet_status = $time_sheet->status;
        $time_sheet_spv = $time_sheet->responsible_spv;



        if( in_array($current_user_role, [2,3,4,5,9]) && $time_sheet_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can return time sheets
            // from staff they are supervising

            $new_time_sheet_status = 50;//'Approved, Skip HRM AND MD'
            $approval_level = 'spv';

            if($time_sheet_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();

                //record time_sheet approval
                $approval = new TimeSheetApproval();
                $approval->time_sheet_id = $time_sheet_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                // event( new TimeSheetApprovedBySupervisorEvent($time_sheet));

                $message = 'Time Sheet Approved successfully.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to approve this time sheet.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $time_sheet_status == 30){ //for HRM


            $approval_level = 'hrm';
            $new_time_sheet_status = 50;// '50' => 'Approved' //skip MD Approval

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet approval
            $approval = new TimeSheetApproval();
            $approval->time_sheet_id = $time_sheet_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            // event( new TimeSheetApprovedByHRMEvent($time_sheet));

            //import time sheets into nav
            //event( new SendTimeSheetToBC130());

            $message = 'Time Sheet Approved successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

        }

        if($current_user_role == 2 && $time_sheet_status == 40){ //for MD

            $approval_level = 'md';
            $new_time_sheet_status = 50;// '50' => 'Approved',

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet approval
            $approval = new TimeSheetApproval();
            $approval->time_sheet_id = $time_sheet_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            // event( new TimeSheetApprovedByMDEvent($time_sheet));

            $message = 'Time Sheet Approved successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);



        }

        if($current_user_role == 1){ //for Super Administrator


            if($time_sheet_status == 20 || $time_sheet_status == 30 || $time_sheet_status == 40){ //Waiting For Supervisor,HRM or MD Approval

                $new_time_sheet_status = 30;//default
                $approval_level = 'spv';//default
                $done_by = $current_user_staff_id;

                //get HRM and MD
                $hrm_user = User::where('role_id', '=','3')->first();
                $md_user = User::where('role_id', '=','2')->first();
                $hrm = $hrm_user->staff;
                $md = $md_user->staff;

                $approval_month = $time_sheet->month + 1;
                $approval_year = $time_sheet->year;
                if($time_sheet->month == '12'){
                    $approval_month = 1;
                    $approval_year = $time_sheet->year + 1;
                }

                $spv_approval_date = $approval_year.'-'.$approval_month.'-'.'01';
                $hrm_approval_date = $approval_year.'-'.$approval_month.'-'.'02';
                $md_approval_date = $approval_year.'-'.$approval_month.'-'.'03';
                $spv_approval_date = MyFunctions::convert_date_to_mysql_format($spv_approval_date);
                $hrm_approval_date = MyFunctions::convert_date_to_mysql_format($hrm_approval_date);
                $md_approval_date = MyFunctions::convert_date_to_mysql_format($md_approval_date);
                $spv_approval_date = $spv_approval_date.' 10:16:43';
                $hrm_approval_date = $hrm_approval_date.' 11:16:43';
                $md_approval_date = $md_approval_date.' 13:16:43';

//                dump($spv_approval_date);
//                dump($hrm_approval_date);
//                dd($md_approval_date);



                if($time_sheet_status == 20){
                    $new_time_sheet_status = 30;//'Waiting For HRM Approval'
                    $approval_level = 'spv';
                    $done_by = $time_sheet_spv;
                    $created_at = $spv_approval_date;
                    $updated_at = $spv_approval_date;
                }

                if($time_sheet_status == 30){
                    $new_time_sheet_status = 50;//'Approved' skip 'Waiting For MD Approval'
                    $approval_level = 'hrm';
                    $done_by = $hrm->id;
                    $created_at = $hrm_approval_date;
                    $updated_at = $hrm_approval_date;
                }

                if($time_sheet_status == 40){
                    $new_time_sheet_status = 50;//Approved
                    $approval_level = 'md';
                    $done_by = $md->id;
                    $created_at = $md_approval_date;
                    $updated_at = $md_approval_date;
                }

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();



                //record time_sheet approval
                $approval = new TimeSheetApproval();
                $approval->time_sheet_id = $time_sheet_id;
                $approval->level = $approval_level;
                $approval->done_by = $done_by;
                $approval->comments = $comments;
                $approval->created_at = $created_at;
                $approval->updated_at = $updated_at;
                $approval->save();

                //send email notification

                $message = 'Time Sheet Approved successfully.';

            }


            elseif($time_sheet_status == 50){ //Approved

                $message = 'Time Sheet Have Already Been Approved.';

            }

            else{

                $message = 'You are not allowed to approve this Time Sheet.';
            }

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to approve this Time Sheet.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

        }

    }


    public function returnTimeSheet(Request $request){

        if (Gate::denies('access',['return_time_sheet','edit'])){
            abort(403, 'You Are Not Allowed To Return This Time Sheet');
        }


        $data = $this->validateReturnRequest();
        $time_sheet_id= $data['time_sheet_id'];
        $comments = $data['comments'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $time_sheet = TimeSheet::find($time_sheet_id);
        $time_sheet_status = $time_sheet->status;
        $time_sheet_spv = $time_sheet->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $time_sheet_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can return time sheets
            // from staff they are supervising

            if($time_sheet_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                $new_time_sheet_status = 0;//'Returned For Correction'
                $return_level = 'spv';

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();

                //record time_sheet return
                $return = new TimeSheetReturn();
                $return->time_sheet_id = $time_sheet_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notifications
                // event( new TimeSheetReturnedEvent($return_level,$time_sheet));

                $message = 'Time Sheet Returned Successfully.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Return this Time Sheet.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $time_sheet_status == 30){ //for HRM

            $return_level = 'hrm';
            $new_time_sheet_status = 0;//'Returned For Correction'

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet approval
            $return = new TimeSheetReturn();
            $return->time_sheet_id = $time_sheet_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            // event( new TimeSheetReturnedEvent($return_level,$time_sheet));

            $message = 'Time Sheet Returned Successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);


        }


        if($current_user_role == 2 && $time_sheet_status == 40){ //for MD

            $return_level = 'md';
            $new_time_sheet_status = 0;//'Returned For Correction'

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet approval
            $return = new TimeSheetReturn();
            $return->time_sheet_id = $time_sheet_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            // event( new TimeSheetReturnedEvent($return_level,$time_sheet));

            $message = 'Time Sheet Returned Successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);



        }


        if($current_user_role == 1){ //for Super Administrator


            if($time_sheet_status == 20 || $time_sheet_status == 30 || $time_sheet_status == 40 || $time_sheet_status == 50){ //can return submitted time sheet at any status

                $new_time_sheet_status = 0;
                $return_level = 'spv';

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();

                //record time_sheet approval
                $return = new TimeSheetReturn();
                $return->time_sheet_id = $time_sheet_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notification to  new supervisor

                $message = 'Time Sheet Returned successfully.';

            }

            else{

                $message = 'You are not allowed to Return this Time Sheet.';
            }

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to Return this Time Sheet.';
            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

        }

    }


    public function changeSupervisor(Request $request){

        if (Gate::denies('access',['change_supervisor','edit'])){
            abort(403, 'You Are Not Allowed To Change Supervisor For This Time Sheet');
        }

        $data = $this->validateChangeSupervisorRequest();
        $time_sheet_id = $data['time_sheet_id'];
        $new_spv = $data['responsible_spv'];
        $reason_for_change = $data['reason'];

        //get old supervisor
        $time_sheet = TimeSheet::find($time_sheet_id);
        $old_spv = $time_sheet->responsible_spv;

        //change supervisor, but when you change supervisor also reverse status to previous stage
        try{
            $time_sheet->responsible_spv = $new_spv;
            $time_sheet->status = '20';
            $time_sheet->save();

            $changed_by = auth()->user()->staff->id;

            //record this change
            $spv_change = new TimeSheetChangedSupervisor();
            $spv_change->time_sheet_id = $time_sheet_id;
            $spv_change->old_spv_id = $old_spv;
            $spv_change->new_spv_id = $new_spv;
            $spv_change->changed_by = $changed_by;
            $spv_change->reason = $reason_for_change;
            $spv_change->save();

            //send email notification to  new supervisor

            $message = 'Supervisor Have been Changed Successfully';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

        }catch(\Exception $e){
            echo $e->getMessage();
        };

    }


    public function rejectTimeSheet(Request $request){

        if (Gate::denies('access',['reject_time_sheet','edit'])){
            abort(403, 'You Are Not Allowed To Reject This Time Sheet');
        }


        $data = $this->validateRejectRequest();
        $time_sheet_id= $data['time_sheet_id'];
        $rejection_reason = $data['rejection_reason'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $time_sheet = TimeSheet::find($time_sheet_id);
        $time_sheet_status = $time_sheet->status;
        $time_sheet_spv = $time_sheet->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $time_sheet_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can reject time sheets
            // from staff they are supervising

            if($time_sheet_spv == $current_user_staff_id){ //reject only if the current user is the supervisor assigned to approve the request

                $new_time_sheet_status = 99;//'Rejected'
                $reject_level = 'spv';

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();

                //record time_sheet approval
                $reject = new TimeSheetReject();
                $reject->time_sheet_id = $time_sheet_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                // event( new TimeSheetRejectedEvent($reject_level,$time_sheet));

                $message = 'Time Sheet Rejected Successfully.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Reject this Time Sheet.';

                return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

            }


        }

        if($current_user_role == 3 && $time_sheet_status == 30){ //for HRM

            $reject_level = 'hrm';
            $new_time_sheet_status = 99;//'Rejected For Correction'

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet approval
            $reject = new TimeSheetReject();
            $reject->time_sheet_id = $time_sheet_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            // event( new TimeSheetRejectedEvent($reject_level,$time_sheet));

            $message = 'Time Sheet Rejected Successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);



        }

        if($current_user_role == 2 && $time_sheet_status == 40 ){ //for MD

            $reject_level = 'md';
            $new_time_sheet_status = 99;//'Rejected For Correction'

            //update time_sheet status
            $time_sheet->status = $new_time_sheet_status;
            $time_sheet->save();

            //record time_sheet rjection
            $reject = new TimeSheetReject();
            $reject->time_sheet_id = $time_sheet_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            // event( new TimeSheetRejectedEvent($reject_level,$time_sheet));

            $message = 'Time Sheet Rejected Successfully.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);


        }

        if($current_user_role == 1){ //for Super Administrator


            if($time_sheet_status == 20 || $time_sheet_status == 30 || $time_sheet_status == 40 || $time_sheet_status == 50){ //can reject submitted time sheet at any status

                $new_time_sheet_status = 99;
                $reject_level = 'adm';

                //update time_sheet status
                $time_sheet->status = $new_time_sheet_status;
                $time_sheet->save();

                //record time_sheet approval
                $reject = new TimeSheetReject();
                $reject->time_sheet_id = $time_sheet_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notification
                //event( new TimeSheetRejectedEvent($reject_level,$time_sheet));

                $message = 'Time Sheet Rejected successfully.';

            }

            else{

                $message = 'You are not allowed to reject this Time Sheet.';
            }

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to reject this Time Sheet.';

            return redirect('time_sheet_admin/'.$time_sheet_id)->with('message',$message);

        }

    }




    /********************** VALIDATION SECTION ****************************/

    public function validateApprovalRequest(){

        return  request()->validate([
            'time_sheet_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }

    public function validateReturnRequest(){

        return  request()->validate([
            'time_sheet_id' => 'required',
            'comments' =>  'required',
        ]);

    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'reason' =>  'required',
            'time_sheet_id' => 'required',
        ]);

    }

    public function validateRejectRequest(){

        return  request()->validate([
            'time_sheet_id' => 'required',
            'rejection_reason' =>  'required',
        ]);

    }


}
