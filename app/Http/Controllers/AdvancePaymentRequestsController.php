<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use App\Events\RequestActivityEvent;
use App\Models\GeneralSetting;
use App\Models\Helper;
use App\Models\NumberSeries;
use App\Models\Project;
use App\Rules\TermsRule;
use App\Models\Staff;
use App\Models\Activity;
use App\Models\GlAccount;
use App\Models\AdvancePaymentRequest;
use App\Models\RequestApproval;
use App\Models\RequestSupervisorChange;
use App\Models\RequestRejection;
use App\Models\RequestReturn;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AdvancePaymentRequestsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($status)
    {

        if (Gate::denies('access',['advance_payment_requests','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff != null ? auth()->user()->staff->id : null;
        //dd($employee_id);

        //for my travel requests
        $advance_payment_requests = AdvancePaymentRequest::where('status', '=', $status)->where('staff_id','=',$employee_id)->get();
        $statusesAllowedForEditing = AdvancePaymentRequest::$statusesAllowedForEditing;
        $statusesAllowedForDeleting = AdvancePaymentRequest::$statusesAllowedForDeleting;

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        $request_statuses = AdvancePaymentRequest::$statuses;
        $advance_payment_request_status   = $status;

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed My Advance Payments  Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('advance_payment_requests.index',
            compact('advance_payment_requests', 'request_statuses', 'statusesAllowedForEditing',
                'statusesAllowedForDeleting', 'advance_payment_request_status',
                'model_name', 'controller_name', 'view_type'));
    }


    public function adminIndex( $status )
    {

        if (Gate::denies('access',['advance_payment_requests','view'])){
            abort(403, 'Access Denied');
        }

        $currentUserStaffId = auth()->user()->staff != null ? auth()->user()->staff->id : null;
        $currentUserRoleId = auth()->user()->role_id;
        $advance_payment_requests = [];
        $advance_payment_requests1 = [];
        $advance_payment_requests2 = [];


        if( in_array($currentUserRoleId, [1,2,3,4,9]) ){
            //for Super Administrator or SA, MD, HRM, ACC, FD
            $advance_payment_requests1 = AdvancePaymentRequest::where('status', '=', $status)->get();
        }

        if(in_array($currentUserRoleId,[5,9])){ // for SPV
            $advance_payment_requests2 = AdvancePaymentRequest::where('status','=',$status)->where('responsible_spv','=',$currentUserStaffId)->get();
        }


        foreach ($advance_payment_requests1 as $request1){
            $advance_payment_requests[$request1->id] = $request1;
        }

        foreach ($advance_payment_requests2 as $request2){
            if(!array_key_exists($request2->id, $advance_payment_requests)){
                $advance_payment_requests[$request2->id] = $request2;
            }
        }

        $statusesAllowedForDeletingForOtherStaff = AdvancePaymentRequest::$statusesAllowedForDeletingForOtherStaff;
        $rolesAllowedDeletingForOtherStaff = AdvancePaymentRequest::$rolesAllowedDeletingForOtherStaff;

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        $request_statuses = AdvancePaymentRequest::$statuses;
        $filtered_requests_status = $status;

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed Advance Payments List Administrative Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('advance_payment_requests.admin_index',
            compact('advance_payment_requests', 'request_statuses', 'filtered_requests_status',
                'currentUserStaffId', 'currentUserRoleId', 'statusesAllowedForDeletingForOtherStaff', 'rolesAllowedDeletingForOtherStaff',
                'model_name', 'controller_name', 'view_type'));


    }


    public function myRequestsIndex()
    {

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed My Advance Payments Options Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('advance_payment_requests.my_records_search',
            compact(   'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myRequestsList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff = auth()->user()->staff;
        $staff_id = $staff->id;
        $request_statuses = AdvancePaymentRequest::$statuses;


        $query = AdvancePaymentRequest::query();
        $query = $query->where('staff_id', '=', $staff_id);
        $query = $query->where('status', '=', '50');//approved
        $query = $query->where('year', '=', $year);

        $advance_payment_requests = $query->get();

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed My Advance Payments List Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('advance_payment_requests.my_records_list',
            compact(  'advance_payment_requests',  'year', 'request_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function staffRequestsIndex()
    {

        $year = date('Y');
        $initial_year = 2019;
        $all_staff =  Staff::get_valid_staff_list();

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed All Staff Advance Payments Options Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('advance_payment_requests.staff_records_search',
            compact(   'year', 'initial_year', 'all_staff',
                'model_name', 'controller_name', 'view_type'));

    }


    public function staffRequestsList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff_id = $data['staff_id'];
        $request_statuses = RequestApproval::$statuses;


        $query = AdvancePaymentRequest::query();
        $query = $query->where('status', '=', '50');//approved
        if($staff_id != 'all'){
            $query = $query->where('staff_id', '=', $staff_id);//approved
        }
        $query = $query->where('year', '=', $year);

        $advance_payment_requests = $query->get();

        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'index';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed All Staff Advance Payments List Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('advance_payment_requests.staff_records_list',
            compact(  'advance_payment_requests',  'year', 'staff_id' ,'request_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function create($responseType = null)
    {
        if (Gate::denies('access',['advance_payment_requests','store'])){
            abort(403, 'Access Denied');
        }

        $current_user = auth()->user();
        $user_role = $current_user->role_id;
        $current_staff = $current_user->staff;

        $advance_payment_request = new AdvancePaymentRequest();
        $advancePaymentNumberSeries = NumberSeries::get_next_number_from_series('1');
        $advance_payment_request->no = $advancePaymentNumberSeries['code'];

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $current_staff == null ? null : $current_staff->supervisor_id;
        $advance_payment_request->responsible_spv = $supervisor_id ?? "";

        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        //get all gs from the gls table
        $accounts_codes = GlAccount::all();
        $supervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $year = date('Y');
        $current_month = date('m');
        $advance_payment_requests_summary = $advance_payment_request->get_summary_for_staff($current_staff->id ?? null,$year);
        $terms = [];

        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($year, $current_month, "objects");

        $employee_name = $current_staff == null ? $current_user->name : ucwords($current_staff->first_name.' '.$current_staff->last_name);


        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'create';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> '',
            'description'=> 'Viewed Advance Payment Requesting Page',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('advance_payment_requests.create',
            compact( 'advance_payment_request',  'responseType', 'projects', 'accounts_codes',
                'supervisors', 'responsible_spv', 'employee_name', 'terms', 'supervisors_mode','year',
                'model_name', 'controller_name', 'view_type', 'user_role'));

    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['advance_payment_requests','store'])){
            abort(403, 'Access Denied');
        }

        //dd($request->all());
        $data = $this->validateRequest();

        //get travel request header data
        $project_id = $data['project_id'];
        $responsible_spv = $data['responsible_spv'];
        $request_date = Helper::convert_date_to_mysql_format($data['request_date']);
        $purpose = $data['purpose'];
        $terms = json_encode($data['terms']);
        $staff_id = auth()->user()->staff != null ? auth()->user()->staff->id : null;
        $details = $data['details'];
        $total = str_replace(",", "", $data['total']);

        $advancePaymentNumberSeries = NumberSeries::get_next_number_from_series('1');
        $project = Project::find($project_id);

        //save travel request header
        $advance_payment_request  = new AdvancePaymentRequest();
        $advance_payment_request->no = $advancePaymentNumberSeries['code'];
        $advance_payment_request->staff_id = $staff_id;
        $advance_payment_request->project_id = $project_id;
        $advance_payment_request->project_code = $project->number;
        $advance_payment_request->year = date('Y');
        $advance_payment_request->request_date = $request_date;
        $advance_payment_request->purpose = $purpose;
        $advance_payment_request->details = $details;
        $advance_payment_request->total = $total;
        $advance_payment_request->terms = $terms;
        $advance_payment_request->responsible_spv = $responsible_spv;
        $advance_payment_request->status = '10';
        $advance_payment_request->transferred_to_nav = 'no';
        $advance_payment_request->nav_id = null;
        $advance_payment_request->save();

        if(isset($advance_payment_request->id)){
            //Save last number used for this item
            NumberSeries::save_last_number_used($advancePaymentNumberSeries);

            //Store attachments
            $this->storeAttachments($advance_payment_request);

            //Send Notifications
            $staff_request = $advance_payment_request;
            $request_name = "Advance Payment";
            $request_category = "advance_payment_requests";
            $action_name = 'Submitting';
            $action = null;

            event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

            //record user activity
            $activity = [
                'action'=> 'Requested Advance Payment',
                'item'=> 'advance_payment_requests',
                'item_id'=> $advance_payment_request->id,
                'description'=> 'Requested Advance Payment ith ID - '.$advance_payment_request->id,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);

            return redirect('advance_payment_request/'.$advance_payment_request->id);

        }else{

            $message = 'Error! Unable to save new Advance Payment Request';

            return redirect('new_advance_payment_request')->with('message', $message);

        }


    }


    public function show($id, $responseType = null)
    {
        if (Gate::denies('access',['advance_payment_requests','view'])){
            abort(403, 'Access Denied');
        }

        $current_user = auth()->user();
        $user_role = $current_user->role_id;
        $current_staff = $current_user->staff;
        $current_staff_id = $current_staff == null ? null : $current_staff->id;

        $advance_payment_request = AdvancePaymentRequest::find($id);

        if(!isset($advance_payment_request->id)){
            abort(403, "Advance Payment Request does not exist");
        }

        if($current_staff_id == null || $advance_payment_request->staff_id != $current_staff_id){
            abort(403, "You are not authorized to view Advance Payment Request from another Staff.");
        }

        $details = json_decode($advance_payment_request->details);

        $request_statuses = AdvancePaymentRequest::$statuses;
        $responsible_spv = $advance_payment_request->responsible_spv;

        $supervisor = Staff::find($responsible_spv);
        $supervisor_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

        $employee_name = ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name);
        $terms = json_decode($advance_payment_request->terms,true);
        $advance_payment_requests_summary = $advance_payment_request->get_summary_for_staff($advance_payment_request->staff->id,$advance_payment_request->year);

        $returnedByTitle = RequestReturn::returnedByTitle($advance_payment_request->id, "advance_payment_requests");
        $rejectedByTitle = RequestRejection::rejectedByTitle($advance_payment_request->id, "advance_payment_requests");



        $attachments = [];
        if (isset($advance_payment_request->attachments)) {
            $attachments = json_decode($advance_payment_request->attachments);
        }


        //dd( $advance_payment_requests_summary);
        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'show';
        $view_type2 = 'show';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> $advance_payment_request->id,
            'description'=> 'Viewed Information Page Of Advance Payment Request with ID - '.$advance_payment_request->id,
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        //dd($advance_payment_request);

        if( $responseType == "quick-view" || $responseType == "quick-view-with-actions" || $responseType == "message-and-actions"){

            $detailsView =  view('advance_payment_requests.form_display',
                compact( 'advance_payment_request','details', 'attachments', 'responsible_spv', 'supervisor_name',
                    'employee_name', 'request_statuses', 'terms'))->render();

            $messageAndActionsView = view('advance_payment_requests.messages',
                compact( 'advance_payment_request',  'returnedByTitle', 'rejectedByTitle'))->render();

            if($responseType == "quick-view"){
                return $detailsView;
            }
            elseif($responseType == "message-and-actions"){
                return $messageAndActionsView;
            }else{
                $html  = '<div class="row">';
                $html .= '<div class="col-md-9">'.$detailsView.'</div>';
                $html .= '<div class="col-md-3" id="actions-and-messages">'.$messageAndActionsView.'</div>';
                $html .= '</div>';
                return $html;
            }

        }else{

            return view('advance_payment_requests.show',
                compact( 'advance_payment_request','details', 'attachments', 'responsible_spv',
                    'supervisor_name', 'employee_name', 'request_statuses', 'terms','advance_payment_requests_summary',
                    'returnedByTitle', 'rejectedByTitle',
                    'view_type2', 'view_type', 'model_name', 'controller_name'));

        }

    }


    public function showAdmin($id, $responseType = null)
    {
        if (Gate::denies('access',['advance_payment_requests','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;

        $current_user = auth()->user();
        $user_role_id = $current_user->role_id;
        $current_staff = $current_user->staff;
        $user_staff_id = $current_staff == null ? null : auth()->user()->staff->id;

        //dd($advance_payment_request_lines);
        $advance_payment_request = AdvancePaymentRequest::find($id);
        $details = json_decode($advance_payment_request->details);

        $attachments = [];
        if (isset($advance_payment_request->attachments)) {
            $attachments = json_decode($advance_payment_request->attachments);
        }


        $supervisors =  Staff::get_supervisors('2');
        $request_statuses = AdvancePaymentRequest::$statuses;

        $responsible_spv = $advance_payment_request->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $supervisor_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);
        $supervisor_change_reason = "";

        $employee_name = ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name);
        $terms = json_decode($advance_payment_request->terms,true);

        $advance_payment_requests_summary = $advance_payment_request->get_summary_for_staff($advance_payment_request->staff->id,$advance_payment_request->year);

        $isCurrentUserAllowedToApproveThisRequest =
            AdvancePaymentRequest::checkIfCurrentUserIsAllowedToApproveRequest($advance_payment_request->status, $user_role_id, $user_staff_id, $responsible_spv);
        $returnedByTitle = RequestReturn::returnedByTitle($advance_payment_request->id, "advance_payment_requests");
        $rejectedByTitle = RequestRejection::rejectedByTitle($advance_payment_request->id, "advance_payment_requests");


        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'show';
        $view_type2 = 'show_admin';

        $rejection_reason = '';
        $modification_reason = '';
        $comments = '';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> $advance_payment_request->id,
            'description'=> 'Viewed Information Page Of Advance Payment Request with ID - '.$advance_payment_request->id.' In Administrative Mode',
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        if( $responseType == "quick-view" || $responseType == "quick-view-with-actions" || $responseType == "message-and-actions"){

            $detailsView =  view('advance_payment_requests.form_display',
                compact( 'advance_payment_request', 'details', 'attachments', 'supervisors',
                    'responsible_spv', 'supervisor_name', 'employee_name',
                    'request_statuses', 'supervisors_mode', 'terms'))->render();

            $messageAndActionsView = view('advance_payment_requests.messages_and_actions_admin',
                compact( 'advance_payment_request',  'supervisors', 'responsible_spv', 'supervisor_change_reason',
                    'responseType', 'request_statuses', 'supervisors_mode',  'rejection_reason','modification_reason',
                    'comments', 'isCurrentUserAllowedToApproveThisRequest', 'returnedByTitle', 'rejectedByTitle'))->render();

            if($responseType == "quick-view"){
                return $detailsView;
            }elseif($responseType == "message-and-actions"){
                return $messageAndActionsView;
            }else{
                $html  = '<div class="row">';
                $html .= '<div class="col-md-9">'.$detailsView.'</div>';
                $html .= '<div class="col-md-3" id="actions-and-messages">'.$messageAndActionsView.'</div>';
                $html .= '</div>';
                return $html;
            }

        }else{

            return view('advance_payment_requests.show_admin',
                compact( 'advance_payment_request', 'details', 'attachments', 'supervisors', 'supervisor_change_reason',
                    'responsible_spv', 'supervisor_name', 'employee_name', 'request_statuses', 'supervisors_mode',
                    'terms', 'isCurrentUserAllowedToApproveThisRequest', 'returnedByTitle', 'rejectedByTitle',
                    'advance_payment_requests_summary', 'rejection_reason','modification_reason',
                    'comments','user_role_id','view_type2', 'responseType',
                    'model_name', 'controller_name', 'view_type', 'current_staff'));

        }
    }


    public function showStatement($advance_payment_request_id)
    {

        if (Gate::denies('access',['advance_payment_requests','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisors =  Staff::get_supervisors('2');

        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);
        $details = json_decode($advance_payment_request->details);

        $attachments = [];
        if (isset($advance_payment_request->attachments)) {
            $attachments = json_decode($advance_payment_request->attachments);
        }

        $request_statuses = AdvancePaymentRequest::$statuses;
        $advance_payment_requests_summary = $advance_payment_request->get_summary_for_staff($advance_payment_request->staff->id,$advance_payment_request->year);

        $responsible_spv = $advance_payment_request->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $supervisor_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($advance_payment_request->year, date('m',strtotime($advance_payment_request->departure_date)));
        $employee_name = ucwords($advance_payment_request->staff->first_name.' '.$advance_payment_request->staff->last_name);
        $terms = json_decode($advance_payment_request->terms,true);


        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m A');


        $spv = new Staff();
        $spv_approval = RequestApproval::where('request_id','=',$advance_payment_request_id)
            ->where('request_category','=',"advance_payment_requests")
            ->where('level','=','spv')->first();

        $acc = new Staff();
        $acc_approval = RequestApproval::where('request_id','=',$advance_payment_request_id)
            ->where('request_category','=',"advance_payment_requests")
            ->where('level','=','acc')->first();

        $fd = new Staff();
        $fd_approval = RequestApproval::where('request_id','=',$advance_payment_request_id)
            ->where('request_category','=',"advance_payment_requests")
            ->where('level','=','fd')->first();

        $md = new Staff();
        $md_approval = RequestApproval::where('request_id','=',$advance_payment_request_id)
            ->where('request_category','=',"advance_payment_requests")
            ->where('level','=','md')->first();

        if( $advance_payment_request->status == 50 ){
            if( isset($spv_approval->done_by) ){
                $spv = Staff::find($spv_approval->done_by);
            }
            if( isset($acc_approval->done_by) ){ $acc = Staff::find($acc_approval->done_by); }
            if( isset($fd_approval->done_by)  ){ $fd  = Staff::find($fd_approval->done_by); }
            if( isset($md_approval->done_by)  ){ $md  = Staff::find($md_approval->done_by); }
        }

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'advance_payment_requests',
            'item_id'=> $advance_payment_request->id,
            'description'=> 'Viewed Statement Of Advance Payment Request with ID - '.$advance_payment_request->id,
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('advance_payment_requests.statement',
            compact( 'advance_payment_request','details', 'attachments', 'request_statuses',
                'projects','employee_name','terms','supervisors_mode','supervisors','advance_payment_requests_summary',
                'generated_by','generation_date', 'supervisor_name',
                'md','md_approval', 'spv_approval','spv','fd_approval', 'fd','acc_approval', 'acc'));

    }


    public function edit($id, $responseType = null)
    {
        $advance_payment_request = AdvancePaymentRequest::find($id);

        $current_user = auth()->user();
        $user_role = $current_user->role_id;
        $current_staff = $current_user->staff;
        $current_staff_id = $current_staff == null ? null : $current_staff->id;

        if(request()->ajax())
        {
            if (Gate::denies('access',['advance_payment_requests','edit'])) return User::userPermissionDeniedResponse();
            if(!isset($advance_payment_request->id)) return User::itemNotFoundResponse("Advance Payment Request");
            if($current_staff_id == null || $advance_payment_request->staff_id != $current_staff_id)
                return User::staffIsNotAllowedToViewAnotherUsersItemResponse("Advance Payment Request");
        }else{
            if (Gate::denies('access',['advance_payment_requests','edit'])){
                abort(403, 'Access Denied');
            }

            if(!isset($advance_payment_request->id)){
                abort(403, "Advance Payment Request does not exist");
            }

            if($current_staff_id == null || $advance_payment_request->staff_id != $current_staff_id){
                abort(403, "You are not authorized to view Advance Payment Request from another Staff.");
            }
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $current_staff == null ? null : $current_staff->supervisor_id;
        $advance_payment_request->responsible_spv = $supervisor_id ?? "";

        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        //get all gs from the gls table
        $accounts_codes = GlAccount::all();
        $supervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $year = date('Y');
        $current_month = date('m');
        $advance_payment_requests_summary = $advance_payment_request->get_summary_for_staff($current_staff->id ?? null,$year);
        $terms = json_decode($advance_payment_request->terms,true);

        $project = new Project();
        $projects = $project->process_active_projects_for_a_month($year, $current_month, "objects");

        $employee_name = $current_staff == null ? $current_user->name : ucwords($current_staff->first_name.' '.$current_staff->last_name);

        $returnedByTitle = RequestReturn::returnedByTitle($advance_payment_request->id, "advance_payment_requests");
        $rejectedByTitle = RequestRejection::rejectedByTitle($advance_payment_request->id, "advance_payment_requests");


        $model_name = 'advance_payment_request';
        $controller_name = 'advance_payment_requests';
        $view_type = 'create';

        //record user activity
        $activity = [
            'action'=> 'Edit',
            'item'=> 'advance_payment_requests',
            'item_id'=> $advance_payment_request->id,
            'description'=> 'Viewed Editing Page Of Advance Payment Request with ID - '.$advance_payment_request->id,
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        if( $responseType == "quick-edit" ){

            $detailsView =  view('advance_payment_requests.form',
                compact( 'advance_payment_request',  'responseType', 'projects',
                    'accounts_codes', 'supervisors', 'responsible_spv', 'employee_name', 'terms', 'supervisors_mode','year',
                    'model_name', 'controller_name', 'view_type', 'user_role'))->render();

            $messageAndActionsView = view('advance_payment_requests.messages',
                compact( 'advance_payment_request',  'returnedByTitle', 'rejectedByTitle'))->render();

            $html  = '<div class="row">';
            $html .= '<div class="col-md-10">'.$detailsView.'</div>';
            $html .= '<div class="col-md-2" id="actions-and-messages">'.$messageAndActionsView.'</div>';
            $html .= '</div>';
            return $html;

        }else{
            return view('advance_payment_requests.edit',
                compact( 'advance_payment_request',  'responseType', 'projects',
                    'accounts_codes', 'supervisors', 'responsible_spv', 'employee_name', 'terms', 'supervisors_mode','year',
                    'model_name', 'controller_name', 'view_type', 'user_role'));
        }
    }


    public function update($advance_payment_request_id)
    {
        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);

        $current_user = auth()->user();
        $user_role = $current_user->role_id;
        $current_staff = $current_user->staff;
        $current_staff_id = $current_staff == null ? null : $current_staff->id;

        if(request()->ajax())
        {
            if (Gate::denies('access',['advance_payment_requests','edit'])) return User::userPermissionDeniedResponse();
            if(!isset($advance_payment_request->id)) return User::itemNotFoundResponse("Advance Payment Request");
            if($current_staff_id == null || $advance_payment_request->staff_id != $current_staff_id)
                return User::staffIsNotAllowedToViewAnotherUsersItemResponse("Advance Payment Request");
        }else{
            if (Gate::denies('access',['advance_payment_requests','edit'])){
                abort(403, 'Access Denied');
            }

            if(!isset($advance_payment_request->id)){
                abort(403, "Advance Payment Request does not exist");
            }

            if($current_staff_id == null || $advance_payment_request->staff_id != $current_staff_id){
                abort(403, "You are not authorized to view Advance Payment Request from another Staff.");
            }
        }

        //dd($request->all());
        $data = $this->validateRequest();

        //get travel request header data
        $project_id = $data['project_id'];
        $responsible_spv = $data['responsible_spv'];
        $request_date = Helper::convert_date_to_mysql_format($data['request_date']);
        $purpose = $data['purpose'];
        $terms = json_encode($data['terms']);
        $details = $data['details'];
        $total = str_replace(",", "", $data['total']);
        $project = Project::find($project_id);


        $advance_payment_request->project_id = $project_id;
        $advance_payment_request->project_code = $project->number;
        $advance_payment_request->request_date = $request_date;
        $advance_payment_request->purpose = $purpose;
        $advance_payment_request->details = $details;
        $advance_payment_request->total = $total;
        $advance_payment_request->terms = $terms;
        $advance_payment_request->responsible_spv = $responsible_spv;
        $advance_payment_request->status = '10';
        $advance_payment_request->save();

        //Store attachments
        $this->storeAttachments($advance_payment_request);

        //Send Notifications
        $staff_request = $advance_payment_request;
        $request_name = "Advance Payment";
        $request_category = "advance_payment_requests";
        $action_name = 'Submitting';
        $action = null;
        event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

        $activity = [
            'action'=> 'Update',
            'item'=> 'advance_payment_requests',
            'item_id'=> $advance_payment_request->id,
            'description'=> 'Updated Advance Payment Request with ID - '.$advance_payment_request->id,
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        //Handle ajax response here
        return redirect('advance_payment_request/'.$advance_payment_request->id);

    }


    private function storeAttachments($advance_payment_request){
        if ( request()->has('attachments') ){
            if(count(request()->attachments)>0){

                $allowedFileExtension=['pdf','jpg','png','docx','doc','zip'];

                $attachment_names = [];
                $files = request()->file('attachments');
                foreach($files as $file){

                    $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check = in_array($extension,$allowedFileExtension);
                    //dd($check);
                    if($check)
                    {
                        //$attachment_names[] = $file->store('advance_payment_requests/attachments','public');

                        $custom_file_name = time().'-'.str_replace(" ", "-", $filename);
                        $attachment_names[] = $file->storeAs('advance_payment_requests/attachments', $custom_file_name, "public");
                    }

                }

                $advance_payment_request->update([
                    'attachments' => json_encode($attachment_names)
                ]);

            }
        }

    }


    public function viewDocument($filename){
        // Check if file exists in app/storage/file folder
        //dd($filename);
        $file_path = storage_path() . "\\app\\public\\advance_payment_requests\\attachments\\" . $filename;
        //dd($file_path);
        $headers = array(
            'Content-Type' => 'application/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        );
        if ( file_exists( $file_path ) ) {

            return response()->file($file_path);
            return response()->file($file_path, $headers);

        } else {
            // Error
            exit( 'Requested file does not exist on our server!' );
        }

    }


    public function destroy($id)
    {
        if (Gate::denies('access',['advance_payment_requests','delete'])){
            abort(403, 'Access Denied');
        }

        $advance_payment_request = AdvancePaymentRequest::find($id);
        $status = $advance_payment_request->status;

        if( in_array($status,["0", "10"]) ){
            if(($advance_payment_request->staff_id == auth()->user()->staff->id) || in_array(auth()->user()->role_id, ["1","3"]) ){
                $request_id = $advance_payment_request->id;
                $advance_payment_request->delete();

                $activity = [
                    'action'=> 'Delete',
                    'item'=> 'advance_payment_requests',
                    'item_id'=> $request_id,
                    'description'=> 'Deleted Advance Payment Request with ID - '.$request_id,
                    'user_id'=> auth()->user()->id,
                ];
                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);
            }
        }

        return redirect('advance_payment_requests/'.$status);
    }


    public function deleteMultiple(Request $request)
    {

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if (Gate::denies('access',['advance_payment_requests','delete'])) return User::userPermissionDeniedResponse();
        }else{
            if (Gate::denies('access',['advance_payment_requests','delete'])){
                abort(403, 'Access Denied');
            }
        }

        $requestsIds = json_decode($request->itemsIds,true);

        $message = [];
        $feedback = [
            "status" => "fail",
            "message" => $message,
        ];


        if(count($requestsIds) > 0){
            foreach($requestsIds as $requestsId){
                $advance_payment_request = AdvancePaymentRequest::find($requestsId);

                if(isset($advance_payment_request->id)){
                    $requestNo = $advance_payment_request->no;
                    $advance_payment_request->delete();

                    //record user activity
                    $activity = [
                        'action'=> 'Delete',
                        'item'=> 'advance_payment_requests',
                        'item_id'=> $requestsId,
                        'description'=> 'Deleted Advance Payment Request With ID - '.$requestsId,
                        'user_id'=> auth()->user()->id,
                    ];
                    $activity_category = 'major';
                    UserActivity::record_user_activity($activity, $activity_category);

                    $message[] = $requestNo.' : Advance Payment Request Deleted Successfully';
                }else{
                    $message[] = "Advance Payment Request with id ".$requestsId." was not found";
                }
            }

            $feedback = [
                "status" => 'success',
                "message" => $message,
            ];

        }else{
            $message = "Please select at least on Request to be Deleted";
            $feedback = [
                "status" => "fail",
                "message" => $message,
            ];
        }


        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect()->back()->with('message',$message);
        }
    }


    /************************ REQUEST APPROVING *******************/


    public function approveRequest(Request $request){

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if(auth()->user()->staff == null) return User::userIsNotStaffResponse();
            if (Gate::denies('access',['approve_advance_payment_request','edit'])) return User::userPermissionDeniedResponse();
        }else{
            if (Gate::denies('access',['approve_advance_payment_request','edit'])){
                abort(403, 'You Are Not Allowed To Approve This Advance Payment Request');
            }
            $this->validateApprovalRequest();
        }

        $advance_payment_request_id= $request->advance_payment_request_id;
        $comments = $request->comments;

        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);
        $request_category = "advance_payment_requests";
        $request_name = "Advance Payment";

        $message = "";
        $feedback = [];
        if(isset($advance_payment_request->id)){
            $feedback = RequestApproval::approveSubmittedRequest($advance_payment_request, $request_category, $request_name, $comments);
            $message = $feedback['message'];
        }else{
            $message = "Advance Payment Request was not found";
            $feedback = [
                "status" => 'fail',
                "message" => $message,
            ];
        }

        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect('advance_payment_requests_admin/'.$advance_payment_request_id)->with('message',$message);
        }

    }


    public function approveMultipleRequests(Request $request){

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if(auth()->user()->staff == null) return User::userIsNotStaffResponse();
            if (Gate::denies('access',['approve_advance_payment_request','edit'])) return User::userPermissionDeniedResponse();
        }else{
            if (Gate::denies('access',['approve_advance_payment_request','edit'])){
                abort(403, 'You Are Not Allowed To Approve This Advance Payment Request');
            }
        }


        $requestsIds = json_decode($request->itemsIds,true);


        $message = [];
        $feedback = [
            "status" => "fail",
            "message" => $message,
        ];

        if(count($requestsIds) > 0){
            foreach($requestsIds as $requestsId){
                $advance_payment_request = AdvancePaymentRequest::find($requestsId);
                $request_category = "advance_payment_requests";
                $request_name = "Advance Payment";
                $comments = "";

                if(isset($advance_payment_request->id)){
                    $requestApprovalFeedback = RequestApproval::approveSubmittedRequest($advance_payment_request, $request_category, $request_name, $comments);
                    $message[] = $requestApprovalFeedback['message'];
                }else{
                    $message[] = "Advance Payment Request with id ".$requestsId." was not found";
                }

            }

            $feedback = [
                "status" => 'success',
                "message" => $message,
            ];
        }else{
            $message = "Please select at least on Request to be approved";
            $feedback = [
                "status" => "fail",
                "message" => $message,
            ];
        }


        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect()->back()->with('message',$message);
        }
    }


    public function returnRequest(Request $request){

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if(auth()->user()->staff == null) return User::userIsNotStaffResponse();
            if (Gate::denies('access',['return_advance_payment_request','edit'])) return User::userPermissionDeniedResponse();
            //Put validation here
        }else{
            if (Gate::denies('access',['return_advance_payment_request','edit'])){
                abort(403, 'You Are Not Allowed To Return This Advance Payment Request');
            }

            $this->validateReturnRequest();
        }

        $advance_payment_request_id= $request->advance_payment_request_id;
        $comments = $request->comments;

        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);
        $request_category = "advance_payment_requests";
        $request_name = "Advance Payment";

        $message = "";
        $feedback = [];
        if(isset($advance_payment_request->id)){
            $feedback = RequestReturn::returnSubmittedRequest($advance_payment_request,$comments, $request_category, $request_name);
            $message = $feedback['message'];
        }else{
            $message = "Advance Payment Request was not found";
            $feedback = [
                "status" => 'fail',
                "message" => $message,
            ];
        }

        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect('advance_payment_requests_admin/'.$advance_payment_request_id)->with('message',$message);
        }

    }


    public function changeSupervisor(Request $request){

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if(auth()->user()->staff == null) return User::userIsNotStaffResponse();
            if (Gate::denies('access',['change_supervisor','edit'])) return User::userPermissionDeniedResponse();

            //Put validation here
        }else{
            if (Gate::denies('access',['change_supervisor','edit'])){
                abort(403, 'You Are Not Allowed To Change Supervisor For This Advance Payment Request');
            }
            $this->validateChangeSupervisorRequest();
        }


        $advance_payment_request_id= $request->advance_payment_request_id;
        $new_spv = $request->responsible_spv;
        $comments = $request->comments;

        //get old supervisor
        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);
        $request_category = "advance_payment_requests";
        $request_name = "Advance Payment";

        $message = "";
        $feedback = [];
        if(isset($advance_payment_request->id)){
            $feedback = RequestSupervisorChange::changeSupervisor($advance_payment_request, $new_spv, $comments, $request_category, $request_name);
            $message = $feedback['message'];
        }else{
            $message = "Advance Payment Request was not found";
            $feedback = [
                "status" => 'fail',
                "message" => $message,
            ];
        }

        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect('advance_payment_requests_admin/'.$advance_payment_request_id)->with('message',$message);
        }

    }


    public function rejectRequest(Request $request){

        if($request->ajax())
        {
            if(!Auth::check()) return User::authenticationFailedResponse();
            if(auth()->user()->staff == null) return User::userIsNotStaffResponse();
            if (Gate::denies('access',['reject_advance_payment_request','edit'])) return User::userPermissionDeniedResponse();
            //Put validation here
        }else{
            if (Gate::denies('access',['reject_advance_payment_request','edit'])){
                abort(403, 'You Are Not Allowed To Reject This Advance Payment Request');
            }
            $this->validateRejectRequest();
        }


        $advance_payment_request_id= $request->advance_payment_request_id;
        $comments = $request->comments;

        $advance_payment_request = AdvancePaymentRequest::find($advance_payment_request_id);
        $request_category = "advance_payment_requests";
        $request_name = "Advance Payment";

        $message = "";
        $feedback = [];
        if(isset($advance_payment_request->id)){
            $feedback = RequestRejection::rejectSubmittedRequest($advance_payment_request,$comments, $request_category, $request_name);
            $message = $feedback['message'];
        }else{
            $message = "Advance Payment Request was not found";
            $feedback = [
                "status" => 'fail',
                "message" => $message,
            ];
        }

        if($request->ajax()){
            return response()->json($feedback);
        }else{
            return redirect('advance_payment_requests_admin/'.$advance_payment_request_id)->with('message',$message);
        }

    }


    /********************** VALIDATION SECTION ****************************/


    public function validateRequest(){

        return  request()->validate([
            'project_id' => 'required',
            'responsible_spv' =>  'required',
            'request_date' =>  'required',
            'purpose' =>  'required',
            'details' =>  'required',
            'total' =>  'required',
            'terms' =>   new TermsRule,
        ]);

    }


    public function validateApprovalRequest(){

        return  request()->validate([
            'advance_payment_request_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }


    public function validateReturnRequest(){

        return  request()->validate([
            'advance_payment_request_id' => 'required',
            'comments' =>  'required',
        ]);

    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'comments' =>  'required',
            'advance_payment_request_id' => 'required',
        ]);

    }


    public function validateRejectRequest(){

        return  request()->validate([
            'advance_payment_request_id' => 'required',
            'comments' =>  'required',
        ]);

    }


}
