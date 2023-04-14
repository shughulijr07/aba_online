<?php

namespace App\Http\Controllers;

use App\Events\SendTravelRequestToBC130;
use App\Events\TravelRequestApprovedByAccountantEvent;
use App\Events\TravelRequestApprovedByFDEvent;
use App\Events\TravelRequestApprovedByMDEvent;
use App\Events\TravelRequestApprovedBySupervisorEvent;
use App\Events\TravelRequestRejectedEvent;
use App\Events\TravelRequestReturnedEvent;
use App\Events\TravelRequestSubmittedEvent;
use App\Models\GeneralSetting;
use App\Models\MyFunctions;
use App\Models\Project;
use App\Rules\TermsRule;
use App\Models\Staff;
use App\Models\Activity;
use App\Models\GlAccount;
use App\Models\TravelRequest;
use App\Models\TravelRequestApproval;
use App\Models\TravelRequestChangedSupervisor;
use App\Models\TravelRequestLine;
use App\Models\TravelRequestReject;
use App\Models\TravelRequestReturn;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelRequestsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index($status)
    {

        if (Gate::denies('access',['travel_requests','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        //dd($employee_id);

        //for my travel requests
        $travel_requests = TravelRequest::where('status', '=', $status)->where('staff_id','=',$employee_id)->get();

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';


        $travel_request_statuses = TravelRequest::$travel_request_statuses;
        $travel_request_status   = $status;

        return view('travel_requests.index',
            compact('travel_requests', 'travel_request_statuses', 'travel_request_status',
                'model_name', 'controller_name', 'view_type'));


    }


    public function adminIndex( $status )
    {

        if (Gate::denies('access',['travel_requests','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        //Logged in staff ID
        //$logged_staff = $employee_id;
        $current_user_role = auth()->user()->role_id;

        $travel_requests = [];

        if( in_array($current_user_role, [1,2,3,4,9]) ){
            //for Super Administrator or SA, MD, HRM, ACC, FD
            $travel_requests = TravelRequest::where('status', '=', $status)->get();
        }

        if(in_array($current_user_role,[5,9])){ // for SPV
            $travel_requests = TravelRequest::where('status', '=', $status)->where('responsible_spv','=',$employee_id)->get();

        }

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';

        $travel_request_statuses = TravelRequest::$travel_request_statuses;
        $travel_request_status = $status;

        return view('travel_requests.admin_index',
            compact('travel_requests', 'travel_request_statuses','travel_request_status',
                'model_name', 'controller_name', 'view_type'));


    }


    public function myTravelRequestsIndex()
    {

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';

        return view('travel_requests.my_records_search',
            compact(   'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myTravelRequestsList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff = auth()->user()->staff;
        $staff_id = $staff->id;
        $travel_request_statuses = TravelRequest::$travel_request_statuses;


        $query = TravelRequest::query();
        $query = $query->where('staff_id', '=', $staff_id);
        $query = $query->where('status', '=', '50');//approved
        $query = $query->where('year', '=', $year);

        $travel_requests = $query->get();

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';

        return view('travel_requests.my_records_list',
            compact(  'travel_requests',  'year','months','travel_request_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function staffTravelRequestsIndex()
    {

        $year = date('Y');
        $initial_year = 2019;
        $all_staff =  Staff::get_valid_staff_list();

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';

        return view('travel_requests.staff_records_search',
            compact(   'year', 'initial_year', 'all_staff',
                'model_name', 'controller_name', 'view_type'));

    }


    public function staffTravelRequestsList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff_id = $data['staff_id'];
        $travel_request_statuses = TravelRequest::$travel_request_statuses;


        $query = TravelRequest::query();
        $query = $query->where('status', '=', '50');//approved
        if($staff_id != 'all'){
            $query = $query->where('staff_id', '=', $staff_id);//approved
        }
        $query = $query->where('year', '=', $year);

        $travel_requests = $query->get();

        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'index';

        return view('travel_requests.staff_records_list',
            compact(  'travel_requests',  'year', 'staff_id' ,'travel_request_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function create()
    {
        if (Gate::denies('access',['travel_requests','store'])){
            abort(403, 'Access Denied');
        }

        $user_role = auth()->user()->role_id;
        $current_logged_staff = auth()->user()->staff;
        $travel_request = new TravelRequest();

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $current_logged_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        //get all gs from the gls table
        $accounts_codes = GlAccount::all();
        $supervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $year = date('Y');
        $current_month = date('m');
        $travel_requests_summary = $travel_request->get_travel_summary_for_staff($current_logged_staff->id,$year);
        $terms = [];

        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($year, $current_month);

        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);


        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'create';

        return view('travel_requests.create',
            compact( 'travel_request', 'travel_requests_summary','projects',
                'supervisors', 'responsible_spv', 'employee_name', 'terms',
                'supervisors_mode','year',
                'model_name', 'controller_name', 'view_type', 'user_role'));


    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['travel_requests','store'])){
            abort(403, 'Access Denied');
        }

        $file = $request->file('file');
        $filename= time().'.'.$file->getClientOriginalExtension();
        $request->file->move('assets', $filename);


        $data = $request->all();
        $header_data = $this->validateTravelRequest();

        //get travel request header data
        $project_code = $header_data['project_code'];
        $responsible_spv = $header_data['responsible_spv'];
        $departure_date = MyFunctions::convert_date_to_mysql_format($header_data['departure_date']);
        $returning_date = MyFunctions::convert_date_to_mysql_format($header_data['returning_date']);
        $purpose_of_trip = $header_data['purpose_of_trip'];
        $terms = json_encode($header_data['terms']);
        $staff_id = auth()->user()->staff->id;


        //check if there is travel request have been made before in this dates by same user
        $travel_request = TravelRequest::get_staff_travel_request_by_date($staff_id, $departure_date,$returning_date);


        if($travel_request == null){

            //save travel request header
            $travel_request  = new TravelRequest();

            if($request->hasFile('file')){
                $travel_request->file = $filename;
                $travel_request->staff_id = $staff_id;
                $travel_request->project_code = $project_code;
                $travel_request->year = date('Y');
                $travel_request->departure_date = $departure_date;
                $travel_request->returning_date = $returning_date;
                $travel_request->purpose_of_trip = $purpose_of_trip;
                $travel_request->terms = $terms;
                $travel_request->responsible_spv = $responsible_spv;
                $travel_request->status = '10'; //add to drafts
                $travel_request->transferred_to_nav = 'no';
                $travel_request->save();
            }else{
                $travel_request->file = "";
                $travel_request->staff_id = $staff_id;
                $travel_request->project_code = $project_code;
                $travel_request->year = date('Y');
                $travel_request->departure_date = $departure_date;
                $travel_request->returning_date = $returning_date;
                $travel_request->purpose_of_trip = $purpose_of_trip;
                $travel_request->terms = $terms;
                $travel_request->responsible_spv = $responsible_spv;
                $travel_request->status = '10'; //add to drafts
                $travel_request->transferred_to_nav = 'no';
                $travel_request->save();
            }

            //save travel request lines

            //get travel request lines
            $lines = array_slice($data, 6);

            array_pop($lines);
            $poped_lines = $lines;

            array_pop($poped_lines);
            $lines = $poped_lines;

            //save travel request lines
            $travel_request_line = new TravelRequestLine();
            $travel_request_line->travel_request_id = $travel_request->id;
            $travel_request_line->data = json_encode($lines);
            $travel_request_line->save();

            // if( isset($travel_request_line->id) ){
            //     //dump('inatuma email');
            //     event( new TravelRequestSubmittedEvent($travel_request));
            // }

            return redirect('travel_request/'.$travel_request->id);

        }else{

            $message = 'You have already made another Travel Request with similar Departure & Return Dates';

            return redirect('new_travel_request')->with('message', $message);

        }



    }


    public function activities(Request $request)
    {
          $project_number = $request->id;
        //   dd($project_number);
           $project_ids = Project::select('id')->where('number', '=', $project_number)->get();
           $project_id = $project_ids[0]->id;
           $activities = Activity::select('name')->where('project_id', '=', $project_id)->get();
           //dd($activities);
           return response()->json($activities);

    }


    public function filePreview($id)
    {
        $travel_request = TravelRequest::find($id);
        return view('travel_requests.file_preview', compact('travel_request'));
    }


    public function show($id)
    {
        if (Gate::denies('access',['travel_requests','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $user_role = auth()->user()->role_id;
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;


        $current_logged_staff = auth()->user()->staff;



        //dd($travel_request_lines);
        $travel_request = TravelRequest::find($id);

        $lines = [];

        if( count($travel_request->lines)>0 ){
            $lines = ($travel_request->lines->last())->data;
            $lines = json_decode($lines,true);
        }

        $supervisor_id = $travel_request->responsible_spv;

        $supervisors =  Staff::get_supervisors('2');

        $travel_request_statuses = TravelRequest::$travel_request_statuses;

        $responsible_spv = $travel_request->responsible_spv;

        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);



        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($travel_request->year, date('m',strtotime($travel_request->departure_date)));

        $employee_name = ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name);

        $terms = json_decode($travel_request->terms,true);


        $travel_requests_summary = $travel_request->get_travel_summary_for_staff($travel_request->staff->id,$travel_request->year);

         //dd( $travel_requests_summary);
        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'show';
        $view_type2 = 'show';

        $rejection_reason = '';
        $travel_request_modification_reason = '';
        $comments = '';
        //dd($travel_request);

        return view('travel_requests.show',
            compact( 'travel_request','supervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'lines','travel_request_statuses', 'supervisors_mode','projects','terms','travel_requests_summary',
                'rejection_reason','travel_request_modification_reason','comments', 'show','view_type2',
                'model_name', 'controller_name', 'view_type','user_role'));

    }


    public function showAdmin($id)
    {
        if (Gate::denies('access',['travel_requests','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;


        $current_logged_staff = auth()->user()->staff;
        $user_role = auth()->user()->role_id;

        //dd($travel_request_lines);
        $travel_request = TravelRequest::find($id);

        $lines = [];

        if( count($travel_request->lines)>0 ){
            $lines = ($travel_request->lines->last())->data;
            $lines = json_decode($lines,true);
        }


        $supervisors =  Staff::get_supervisors('2');
        $travel_request_statuses = TravelRequest::$travel_request_statuses;
        $responsible_spv = $travel_request->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($travel_request->year, date('m',strtotime($travel_request->departure_date)));
        $employee_name = ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name);
        $terms = json_decode($travel_request->terms,true);

        $travel_requests_summary = $travel_request->get_travel_summary_for_staff($travel_request->staff->id,$travel_request->year);


        $model_name = 'travel_request';
        $controller_name = 'travel_requests';
        $view_type = 'show';
        $view_type2 = 'show_admin';

        $rejection_reason = '';
        $travel_request_modification_reason = '';
        $comments = '';

        return view('travel_requests.show_admin',
            compact( 'travel_request','supervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'lines','travel_request_statuses', 'supervisors_mode','projects','terms','travel_requests_summary',
                'rejection_reason','travel_request_modification_reason','comments','user_role','view_type2',
                'model_name', 'controller_name', 'view_type', 'current_logged_staff'));
    }


    public function showTravellingStatement($travel_request_id)
    {

        if (Gate::denies('access',['travel_requests','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisors =  Staff::get_supervisors('2');



        $travel_request = TravelRequest::find($travel_request_id);
        $travel_request_statuses = TravelRequest::$travel_request_statuses;
        $travel_requests_summary = $travel_request->get_travel_summary_for_staff($travel_request->staff->id,$travel_request->year);

        $lines = [];

        if( count($travel_request->lines)>0 ){
            $lines = ($travel_request->lines->last())->data;
            $lines = json_decode($lines,true);
        }


        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($travel_request->year, date('m',strtotime($travel_request->departure_date)));
        $employee_name = ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name);
        $terms = json_decode($travel_request->terms,true);


        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m A');

        $spv = new Staff();
        $spv_approval = TravelRequestApproval::where('travel_request_id','=',$travel_request_id)->where('level','=','spv')->first();

        $acc = new Staff();
        $acc_approval = TravelRequestApproval::where('travel_request_id','=',$travel_request_id)->where('level','=','acc')->first();

        $fd = new Staff();
        $fd_approval = TravelRequestApproval::where('travel_request_id','=',$travel_request_id)->where('level','=','fd')->first();

        $md = new Staff();
        $md_approval = TravelRequestApproval::where('travel_request_id','=',$travel_request_id)->where('level','=','md')->first();

        if( $travel_request->status == 50 ){
            if( isset($spv_approval->done_by) ){ $spv = Staff::find($spv_approval->done_by); }
            if( isset($acc_approval->done_by) ){ $acc = Staff::find($acc_approval->done_by); }
            if( isset($fd_approval->done_by)  ){ $fd  = Staff::find($fd_approval->done_by); }
            if( isset($md_approval->done_by)  ){ $md  = Staff::find($md_approval->done_by); }
        }

        //dd($md_approval);


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Travel Request Statement',
            'item_id'=> $travel_request->id,
            'description'=> 'Viewed Statement Of Travel Request with ID - '.$travel_request->id,
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('travel_requests.statement',
            compact( 'travel_request','lines','travel_request_types','travel_request_statuses',
                'projects','employee_name','terms','supervisors_mode','supervisors','travel_requests_summary',
                'generated_by','generation_date',
                'md','md_approval', 'spv_approval','spv','fd_approval', 'fd','acc_approval', 'acc'));

    }


    public function update($travel_request_id)
    {
        if (Gate::denies('access',['travel_requests','edit'])){
            abort(403, 'Access Denied');
        }


        $data = request()->all();
        $header_data = $this->validateTravelRequest();
        //get travel request header data
        $project_code = $header_data['project_code'];
        $responsible_spv = $header_data['responsible_spv'];
        $departure_date = MyFunctions::convert_date_to_mysql_format($header_data['departure_date']);
        $returning_date = MyFunctions::convert_date_to_mysql_format($header_data['returning_date']);
        $purpose_of_trip = $header_data['purpose_of_trip'];
        $terms = json_encode($header_data['terms']);
        $staff_id = auth()->user()->staff->id;


        //save travel request header
        $travel_request  = TravelRequest::find($travel_request_id);
        $travel_request->staff_id = $staff_id;
        $travel_request->project_code = $project_code;
        $travel_request->year = date('Y');
        $travel_request->departure_date = $departure_date;
        $travel_request->returning_date = $returning_date;
        $travel_request->purpose_of_trip = $purpose_of_trip;
        $travel_request->terms = $terms;
        $travel_request->responsible_spv = $responsible_spv;
        $travel_request->status = '10'; //add to drafts
        $travel_request->transferred_to_nav = 'no';
        $travel_request->save();

        //save travel request lines

        //get travel request lines
        $lines = array_slice($data, 7);
        array_pop($lines);

        //save travel request lines
        $travel_request_line = $travel_request->lines->last();
        $travel_request_line->travel_request_id = $travel_request->id;
        $travel_request_line->data = json_encode($lines);
        $travel_request_line->save();

        return redirect('travel_request/'.$travel_request->id);


    }


    public function destroy($id)
    {
        //
    }


    /************************ TRAVEL REQUEST APPROVING *******************/


    public function approveTravelRequest(Request $request){

        if (Gate::denies('access',['approve_travel_request','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Travel Request');
        }

        $data = $this->validateApprovalRequest();
        $travel_request_id= $data['travel_request_id'];
        $comments = $data['comments'];

        return $this->approveSubmittedTravelRequest($travel_request_id,$comments);

    }


    public function approveSubmittedTravelRequest($travel_request_id,$comments = ''){

        if (Gate::denies('access',['approve_travel_request','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Travel Request');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $travel_request = TravelRequest::find($travel_request_id);
        $travel_request_status = $travel_request->status;
        $travel_request_spv = $travel_request->responsible_spv;



        if( (in_array($current_user_role, [2,3,4,5,9])) && $travel_request_status == 10 ){ // for SPV, HRM,ACC, FD and MD
            //Waiting For Supervisor Approval, accountant, hrm,ACC,FD and MD can be supervisors thus they can return travel requests
            // from staff they are supervising

            $new_travel_request_status = 20;//'Waiting For ACC Approval'
            $approval_level = 'spv';

            if($travel_request_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request approval
                $approval = new TravelRequestApproval();
                $approval->travel_request_id = $travel_request_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                event( new TravelRequestApprovedBySupervisorEvent($travel_request));

                $message = 'Travel Request Approved successfully.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to approve this travel request.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

            }

        }

        if($current_user_role == 4 && $travel_request_status == 20){ //for ACC


            $approval_level = 'acc';
            $new_travel_request_status = 30;// Waiting For Finance Director Approval

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $approval = new TravelRequestApproval();
            $approval->travel_request_id = $travel_request_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            event( new TravelRequestApprovedByAccountantEvent($travel_request));

            $message = 'Travel Request Approved successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }

        if($current_user_role == 9 && $travel_request_status == 30){ //for FD


            $approval_level = 'fd';
            $new_travel_request_status = 40;//

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $approval = new TravelRequestApproval();
            $approval->travel_request_id = $travel_request_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            event( new TravelRequestApprovedByFDEvent($travel_request));

            $message = 'Travel Request Approved successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }

        if($current_user_role == 2 && $travel_request_status == 40){ //for MD

            $approval_level = 'md';
            $new_travel_request_status = 50;// '50' => 'Approved',

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $approval = new TravelRequestApproval();
            $approval->travel_request_id = $travel_request_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            event( new TravelRequestApprovedByMDEvent($travel_request));

            //import travel requests into nav
            //event( new SendTravelRequestToBC130());

            $message = 'Travel Request Approved successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);



        }

        if($current_user_role == 1){ //for Super Administrator


            if( in_array($travel_request_status, [10,20,30,40]) ){ //Waiting For Supervisor,ACC,FD or MD Approval

                $new_travel_request_status = 20;//default
                $approval_level = 'spv';//default

                if($travel_request_status == 10){
                    $new_travel_request_status = 20;//'Waiting For ACC Approval'
                    $approval_level = 'spv';
                }

                if($travel_request_status == 20){
                    $new_travel_request_status = 30;//'Waiting For ACC Approval'
                    $approval_level = 'acc';
                }

                if($travel_request_status == 30){
                    $new_travel_request_status = 40;//'Waiting For FD Approval'
                    $approval_level = 'acc';
                }

                if($travel_request_status == 40){
                    $new_travel_request_status = 50;//Approved
                    $approval_level = 'md';

                    //import travel requests into nav
                    //event( new SendTravelRequestToBC130());
                }

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request approval
                $approval = new TravelRequestApproval();
                $approval->travel_request_id = $travel_request_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification

                $message = 'Travel Request Approved successfully.';

            }


            elseif($travel_request_status == 50){ //Approved

                $message = 'Travel Request Have Already Been Approved.';

            }

            else{

                $message = 'You are not allowed to approve this Travel Request.';
            }

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to approve this Travel Request.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }

    }


    public function returnTravelRequest(Request $request){

        if (Gate::denies('access',['return_travel_request','edit'])){
            abort(403, 'You Are Not Allowed To Return This Travel Request');
        }


        $data = $this->validateReturnRequest();
        $travel_request_id= $data['travel_request_id'];
        $comments = $data['comments'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $travel_request = TravelRequest::find($travel_request_id);
        $travel_request_status = $travel_request->status;
        $travel_request_spv = $travel_request->responsible_spv;


        if( ( in_array($current_user_role, [2,3,4,5,9])) && $travel_request_status == 10 ){ // for SPV, HRM,ACC, FD and MD
            //Waiting For Supervisor Approval, SPV, HRM,ACC, FD and MD can be supervisors thus they can return travel requests
            // from staff they are supervising

            if($travel_request_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                $new_travel_request_status = 0;//'Returned For Correction'
                $return_level = 'spv';

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request return
                $return = new TravelRequestReturn();
                $return->travel_request_id = $travel_request_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notifications
                event( new TravelRequestReturnedEvent($return_level,$travel_request));

                $message = 'Travel Request Returned Successfully.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Return this Travel Request.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

            }

        }

        if($current_user_role == 4 && $travel_request_status == 20){ //for ACC

            $return_level = 'acc';
            $new_travel_request_status = 0;//'Returned For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $return = new TravelRequestReturn();
            $return->travel_request_id = $travel_request_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new TravelRequestReturnedEvent($return_level,$travel_request));

            $message = 'Travel Request Returned Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);


        }

        if($current_user_role == 9 && $travel_request_status == 30){ //for FD

            $return_level = 'fd';
            $new_travel_request_status = 0;//'Returned For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $return = new TravelRequestReturn();
            $return->travel_request_id = $travel_request_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new TravelRequestReturnedEvent($return_level,$travel_request));

            $message = 'Travel Request Returned Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);


        }


        if($current_user_role == 2 && $travel_request_status == 40){ //for MD

            $return_level = 'md';
            $new_travel_request_status = 0;//'Returned For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $return = new TravelRequestReturn();
            $return->travel_request_id = $travel_request_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new TravelRequestReturnedEvent($return_level,$travel_request));

            $message = 'Travel Request Returned Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);



        }


        if($current_user_role == 1){ //for Super Administrator


            if( in_array($travel_request_status, [10,20,30,40]) ){ //Waiting For Supervisor,ACC,FD or MD Approval

                $new_travel_request_status = 0;
                $return_level = 'spv';

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request approval
                $return = new TravelRequestReturn();
                $return->travel_request_id = $travel_request_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notification to  new supervisor

                $message = 'Travel Request Returned successfully.';

            }

            else{

                $message = 'You are not allowed to Return this Travel Request.';
            }

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to Return this Travel Request.';
            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }

    }


    public function changeSupervisor(Request $request){

        if (Gate::denies('access',['change_supervisor','edit'])){
            abort(403, 'You Are Not Allowed To Change Supervisor For This Travel Request');
        }

        $data = $this->validateChangeSupervisorRequest();
        $travel_request_id = $data['travel_request_id'];
        $new_spv = $data['responsible_spv'];
        $reason_for_change = $data['reason'];

        //get old supervisor
        $travel_request = TravelRequest::find($travel_request_id);
        $old_spv = $travel_request->responsible_spv;

        //change supervisor, but when you change supervisor also reverse status to previous stage
        try{
            $travel_request->responsible_spv = $new_spv;
            $travel_request->status = '10';
            $travel_request->save();

            $changed_by = auth()->user()->staff->id;

            //record this change
            $spv_change = new TravelRequestChangedSupervisor();
            $spv_change->travel_request_id = $travel_request_id;
            $spv_change->old_spv_id = $old_spv;
            $spv_change->new_spv_id = $new_spv;
            $spv_change->changed_by = $changed_by;
            $spv_change->reason = $reason_for_change;
            $spv_change->save();

            //send email notification to  new supervisor

            $message = 'Supervisor Have been Changed Successfully';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }catch(\Exception $e){
            echo $e->getMessage();
        };

    }


    public function rejectTravelRequest(Request $request){

        if (Gate::denies('access',['reject_travel_request','edit'])){
            abort(403, 'You Are Not Allowed To Reject This Travel Request');
        }


        $data = $this->validateRejectRequest();
        $travel_request_id= $data['travel_request_id'];
        $rejection_reason = $data['rejection_reason'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $travel_request = TravelRequest::find($travel_request_id);
        $travel_request_status = $travel_request->status;
        $travel_request_spv = $travel_request->responsible_spv;


        if( ( in_array($current_user_role, [2,3,4,5,9])) && $travel_request_status == 10 ){ // for SPV, HRM,ACC, FD and MD
            //Waiting For Supervisor Approval, SPV, HRM,ACC, FD and MD can be supervisors thus they can reject travel requests
            // from staff they are supervising

            if($travel_request_spv == $current_user_staff_id){ //reject only if the current user is the supervisor assigned to approve the request

                $new_travel_request_status = 99;//'Rejected'
                $reject_level = 'spv';

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request approval
                $reject = new TravelRequestReject();
                $reject->travel_request_id = $travel_request_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                event( new TravelRequestRejectedEvent($reject_level,$travel_request));

                $message = 'Travel Request Rejected Successfully.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Reject this Travel Request.';

                return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

            }


        }

        if($current_user_role == 4 && $travel_request_status == 20){ //for ACC

            $reject_level = 'acc';
            $new_travel_request_status = 99;//'Rejected For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request approval
            $reject = new TravelRequestReject();
            $reject->travel_request_id = $travel_request_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new TravelRequestRejectedEvent($reject_level,$travel_request));

            $message = 'Travel Request Rejected Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);



        }

        if($current_user_role == 9 && $travel_request_status == 30 ){ //for FD

            $reject_level = 'fd';
            $new_travel_request_status = 99;//'Rejected For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request rejection
            $reject = new TravelRequestReject();
            $reject->travel_request_id = $travel_request_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new TravelRequestRejectedEvent($reject_level,$travel_request));

            $message = 'Travel Request Rejected Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);


        }

        if($current_user_role == 2 && $travel_request_status == 40 ){ //for MD

            $reject_level = 'md';
            $new_travel_request_status = 99;//'Rejected For Correction'

            //update travel_request status
            $travel_request->status = $new_travel_request_status;
            $travel_request->save();

            //record travel_request rejection
            $reject = new TravelRequestReject();
            $reject->travel_request_id = $travel_request_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new TravelRequestRejectedEvent($reject_level,$travel_request));

            $message = 'Travel Request Rejected Successfully.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);


        }

        if($current_user_role == 1){ //for Super Administrator


            if( in_array($travel_request_status, [10,20,30,40]) ){ //can reject submitted travel request at any status

                $new_travel_request_status = 99;
                $reject_level = 'adm';

                //update travel_request status
                $travel_request->status = $new_travel_request_status;
                $travel_request->save();

                //record travel_request approval
                $reject = new TravelRequestReject();
                $reject->travel_request_id = $travel_request_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notification
                event( new TravelRequestRejectedEvent($reject_level,$travel_request));

                $message = 'Travel Request Rejected successfully.';

            }

            else{

                $message = 'You are not allowed to reject this Travel Request.';
            }

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to reject this Travel Request.';

            return redirect('travel_requests_admin/'.$travel_request_id)->with('message',$message);

        }

    }


    /********************** VALIDATION SECTION ****************************/


    public function validateTravelRequest(){

        return  request()->validate([
            'project_code' => 'required',
            'responsible_spv' =>  'required',
            'departure_date' =>  'required',
            'returning_date' =>  'required',
            'purpose_of_trip' =>  'required',
            'terms' =>   new TermsRule,
        ]);

    }


    public function validateApprovalRequest(){

        return  request()->validate([
            'travel_request_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }


    public function validateReturnRequest(){

        return  request()->validate([
            'travel_request_id' => 'required',
            'comments' =>  'required',
        ]);

    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'reason' =>  'required',
            'travel_request_id' => 'required',
        ]);

    }


    public function validateRejectRequest(){

        return  request()->validate([
            'travel_request_id' => 'required',
            'rejection_reason' =>  'required',
        ]);

    }


}
