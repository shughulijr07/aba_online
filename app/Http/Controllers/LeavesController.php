<?php

namespace App\Http\Controllers;

use App\Events\LeavePaymentConfirmedEvent;
use App\Events\LeaveRequestRejectedEvent;
use App\Events\LeaveRequestApprovedByMDEvent;
use App\Events\LeaveRequestApprovedByHRMEvent;
use App\Events\LeaveRequestApprovedBySupervisorEvent;
use App\Events\LeaveRequestReceivedEvent;
use App\Models\GeneralSetting;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveApproval;
use App\Models\LeaveChangedSupervisor;
use App\Models\LeaveEntitlement;
use App\Models\LeaveModification;
use App\Models\LeavePayment;
use App\Models\LeavePlan;
use App\Models\LeaveReject;
use App\Models\LeaveType;
use App\Models\MyFunctions;
use App\Models\Staff;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class LeavesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($status)
    {

        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;

        //for my requests
        $leaves = Leave::where('status', '=', $status)->where('employee_id','=',$employee_id)->get();

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'index';


        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        $leave_status = $status;



        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave List Page',
            'item_id'=> '',
            'description'=> 'Viewed My Leave List Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return view('leaves.index',
            compact('leaves', 'leave_statuses', 'leave_types','leave_status', 'model_name', 'controller_name', 'view_type'));

    }


    public function adminIndex( $status )
    {
        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        $current_user_role = auth()->user()->role_id;

        $leaves = [];

        if( in_array($current_user_role,[1,2,3,4])){ //for Super Administrator or HRM
            $leaves = Leave::where('status', '=', $status)->get();
        }

        if(in_array($current_user_role,[5,9])){ // for SPV
            $leaves = Leave::where('status', '=', $status)->where('responsible_spv','=',$employee_id)->get();
        }

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'index';


        $leave_statuses = Leave::$leave_statuses;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        $leave_status = $status;



        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Approving List Page',
            'item_id'=> '',
            'description'=> 'Viewed Approving Leave List Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return view('leaves.index_admin',
            compact('leaves', 'leave_statuses', 'leave_types','leave_status',
                'model_name', 'controller_name', 'view_type'));
    }


    public function myLeavesIndex()
    {

        $year = date('Y');
        $initial_year = 2019;
        $staff = auth()->user()->staff;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        //filter leave types according to gender
        if( $staff->gender == 'Male'){ unset($leave_types['maternity_leave']); unset($leave_types['maternity_leave_2']); }
        elseif( $staff->gender == 'Female'){ unset($leave_types['paternity_leave']); };

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'index';

        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Records Page',
            'item_id'=> '',
            'description'=> 'Viewed Leave Records Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('my_leaves.index',
            compact(  'leave_types', 'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myLeavesList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $leave_type = $data['leave_type'];
        $staff = auth()->user()->staff;
        $employee_id = $staff->id;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        //filter leave types according to gender
        if( $staff->gender == 'Male'){ unset($leave_types['maternity_leave']); unset($leave_types['maternity_leave_2']); }
        elseif( $staff->gender == 'Female'){ unset($leave_types['paternity_leave']); };

        $query = Leave::query();
        $query = $query->where('employee_id', '=', $employee_id);
        $query = $query->where('status', '=', '50');//approved
        $query = $query->where('year', '=', $year);

        if ( $leave_type == 'all') {
            //don't filter leave types
        }else{

            $query = $query->where('type','=',$leave_type);
        }

        $leaves = $query->get();

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'index';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Records List Page',
            'item_id'=> '',
            'description'=> 'Viewed Leave Records List Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('my_leaves.list',
            compact(  'leaves', 'leave_types','leave_type', 'year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function overlappingLeaves($id){

        $leave = Leave::find($id);
        $department = $leave->staff->department->id;
        $overlapping_starting_date = $leave->get_overlapping_leaves('overlapping_starting_date',$id,$department,$leave->starting_date,$leave->ending_date);
        $overlapping_ending_date = $leave->get_overlapping_leaves('overlapping_ending_date',$id,$department,$leave->starting_date,$leave->ending_date);
        $overlapping_in = $leave->get_overlapping_leaves('overlapping_in',$id,$department,$leave->starting_date,$leave->ending_date);
        $overlapping_out = $leave->get_overlapping_leaves('overlapping_out',$id,$department,$leave->starting_date,$leave->ending_date);
        $overlapping_exactly_on_same_dates = $leave->get_overlapping_leaves('overlapping_exactly_on_same_dates',$id,$department,$leave->starting_date,$leave->ending_date);

        $leave_types = LeaveType::get_active_leave_types()['arrays'];



        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Overlapping Leaves List Page',
            'item_id'=> '',
            'description'=> 'Viewed Overlapping Leaves List Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('leaves.overlapping',
            compact('leave','overlapping_exactly_on_same_dates','overlapping_starting_date',
                'overlapping_ending_date','overlapping_in','overlapping_out', 'leave_types'));
    }


    public function create()
    {
        if (Gate::denies('access',['leaves','store'])){
            abort(403, 'Access Denied');
        }

        $current_staff = auth()->user()->staff;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $carry_over_mode  = $system_settings->carry_over_mode;
        $include_holidays_in_leave = $system_settings->include_holidays_in_leave;
        $include_weekends_in_leave = $system_settings->include_weekends_in_leave;

        $supervisor_id = $current_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $staff_id = $current_staff->id;
        $staff_gender = $current_staff->gender;
        $year = date('Y');

        //before anything generate leave entitlement automatically, call the function and it will take care of the rest
        $entitlement = new LeaveEntitlement();
        $entitlement->create_leave_entitlement_for_one_staff($staff_id,$year); // if entitlement exists it will be skipped

        //then do carry over if carry over mode is automatic
        if( $carry_over_mode== 1 ){//automatic mode
            $entitlement->make_carry_over_for_one_staff($staff_id);
        }

        $leave = new Leave();
        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        $leave_type = '';// default type
        $leaveSupervisors = Staff::get_supervisors($supervisors_mode);
        $babies_count = Leave::$babies_count;
        $responsible_spv = '';
        $include_payment = [];

        //get leave plan for this staff
        $leave_plan = LeavePlan::where('staff_id','=',$staff_id)
            ->where('year','=',$year)
            ->where('status','=','50')->first();

        $leave_summary = $leave->get_leave_summary_for_staff($staff_id,$year);
        //dd($leave_summary);

        //filter leave types according to gender
        $unfiltered_leave_types = $leave_types;
        if( $staff_gender == 'Male'){ unset($leave_types['maternity_leave']);  unset($leave_types['maternity_leave_2']);}
        elseif( $staff_gender == 'Female'){ unset($leave_types['paternity_leave']); unset($leave_types['maternity_leave_2']);};

        $holidays1 = ( Holiday::get_all_holidays_in_a_year( date('Y')) )['arrays'] ;
        $holidays2 = ( Holiday::get_all_holidays_in_a_year( date('Y')+1) )['arrays'] ;


        //dd($dates1);

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'create';



        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Requesting Page',
            'item_id'=> '',
            'description'=> 'Viewed Leave Requesting Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);




        return view('leaves.create',
            compact( 'leave','leave_types', 'leave_type','leaveSupervisors', 'responsible_spv', 'include_payment',
                'leave_summary','staff_gender','supervisors_mode','leave_plan','babies_count','unfiltered_leave_types',
                'include_weekends_in_leave','include_holidays_in_leave','holidays1','holidays2',
                'model_name', 'controller_name', 'view_type'));
    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['leaves','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateLeaveRequest();
        $staff_id = auth()->user()->staff->id;
        $starting_date = MyFunctions::convert_date_to_mysql_format($data['starting_date']);
        $ending_date = MyFunctions::convert_date_to_mysql_format($data['ending_date']);

        //dd($request);

        //check if there is another request with the same dates made by this employee
        $similar_date_leaves = Leave::request_with_similar_dates_made_by_same_staff($staff_id,$starting_date,$ending_date);


        $message  = 'You have already requested another leave with same dates, ';
        $message .= 'please change the dates and then submit your request again';

        if( $similar_date_leaves > 0){
            return redirect()->back()->with('message',$message);
        }



        $leave = new Leave();
        $leave->employee_id = $staff_id;

        //if leave type is maternity then check number of babies
        if($data['leave_type'] == 'maternity_leave'){
            $data1 = request()->validate([
                'supporting_document'=>'sometimes|mimes:zip,pdf,jpeg,jpg,png|max:3072',
                'number_of_babies'=>'required'
            ]);

            if( isset($data1['supporting_document'])){
                $supporting_document = $data1['supporting_document'];
                $leave->supporting_document = $supporting_document->store('leave_supporting_documents','public');
            }

            $number_of_babies = $data1['number_of_babies'];
            $leave->number_of_babies = $number_of_babies;

            //extend maternity_leave entitlement for this employee
            Leave::extend_normal_maternity_leave_for_staff($staff_id,date('Y'),$number_of_babies);
        }

        $leave->type = $data['leave_type'];
        $leave->payment = count($data['include_payment']) == 2 ? 'Include' : 'Do Not Include';
        $leave->year = date('Y');
        $leave->starting_date = $starting_date;
        $leave->ending_date = $ending_date;
        $leave->status = '10';

        //for sick_leave supporting document and description is a must
        if( $data['leave_type'] == 'sick_leave' ){
            $data2 = request()->validate([
                'supporting_document'=>'required|mimes:zip,pdf,jpeg,jpg,png|max:3072',
                'description'=>'required'
            ]);


            $supporting_document = $data2['supporting_document'];
            $description = $data2['description'];

            $leave->supporting_document = $supporting_document->store('leave_supporting_documents','public');
            $leave->description = $description;
        }

        //for other leave types description is a must and supporting document is optional
        elseif( $data['leave_type'] == 'other' ||  $data['leave_type'] == 'compassionate_leave' ){
            $data2 = request()->validate([
                'supporting_document'=>'sometimes|mimes:zip,pdf,jpeg,jpg,png|max:3072',
                'description'=>'required'
            ]);

            if( isset($data2['supporting_document'])){
                $supporting_document = $data2['supporting_document'];
                $leave->supporting_document = $supporting_document->store('leave_supporting_documents','public');
            }
            $description = $data2['description'];
            $leave->description = $description;
        }
        else{
            $leave->description = $data['description'];
        }

        $leave->responsible_spv = $data['responsible_spv'];
        $leave->paid_by_accountant = 'no';
        $leave->modified_by_spv = 'no';
        $leave->modified_by_hrm = 'no';
        $leave->modified_by_adm = 'no';
        $leave->save();

        //send email notifications through event
        if( isset($leave->id)){
            event(new LeaveRequestReceivedEvent($leave));


            //record user activity
            $activity = [
                'action'=> 'Requested Leave',
                'item'=> 'Leave',
                'item_id'=> $leave->id,
                'description'=> 'Requested Leave With ID - '.$leave->id,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);

        }

        return redirect('/leave/'.$leave->id);

    }


    public function show($leave_id)
    {

        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }

        $current_staff = auth()->user()->staff;

        $leave = Leave::find($leave_id); //dd($leave->staff->jobTitle->title);

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $leave->responsible_spv;

        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'show';
        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        $leave_statuses = Leave::$leave_statuses;
        $leave_entitlement = LeaveEntitlement::get_leave_entitlements_by_year($leave->employee_id,$leave->year)['arrays'];

        $leaveSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = Leave::get_responsible_supervisor($leave->responsible_spv); //dd($responsible_spv);

        $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
        $leave_modification_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        $modification = '';
        $rejection = '';
        $approval_message = '';

        if ($leave->status == 10){ } // do nothing

        if ($leave->status == 20){ // waiting for hrm approval
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'spv')->latest()->first();
            $comments = $approval->comments;
        }

        if ($leave->status == 30){ // waiting for md approval
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'hrm')->latest()->first();
            $comments = $approval->comments;
        }

        if ($leave->status == 40 || $leave->status == 50){ // approved-waiting payment or approved
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'md')->latest()->first();
            $comments = $approval->comments;

            //get approval message (the approval message will take into consideration all changes made by spv and hrm)
            $approval_message = $this->get_approval_message($leave); //dd($approval_message);

        }

        if ($leave->status == 99){ //rejected
            $rejection = LeaveReject::where('leave_id', '=', $leave_id)->latest()->first();
        }

        $planned_leave = LeavePlan::check_if_requested_leave_is_planned($leave);


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Information Page',
            'item_id'=> $leave->id,
            'description'=> 'Viewed Information Page Of Leave Request with ID - '.$leave->id,
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('leaves.show',
            compact( 'leave', 'leave_types','leave_statuses', 'leave_entitlement', 'leaveSupervisors',
                'responsible_spv','rejection_reason','modification', 'rejection', 'approval_message',
                'leave_modification_reason', 'supervisor_change_reason','comments', 'planned_leave',
                'model_name', 'controller_name', 'view_type'));
    }


    public function showAdmin($leave_id)
    {
        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }

        $leave = Leave::find($leave_id); //dd($leave->staff->jobTitle->title);

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $include_holidays_in_leave = $system_settings->include_holidays_in_leave;
        $include_weekends_in_leave = $system_settings->include_weekends_in_leave;

        $supervisor_id = $leave->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $model_name = 'leave';
        $controller_name = 'leaves';
        $view_type = 'show';

        $leave_types = LeaveType::get_active_leave_types()['arrays']; //dd($leave_types);
        $leave_statuses = Leave::$leave_statuses;
        $leave_entitlement = LeaveEntitlement::get_leave_entitlements_by_year($leave->employee_id,$leave->year)['arrays'];
        $leaveSupervisors = Staff::get_supervisors_list();
        $responsible_spv = Leave::get_responsible_supervisor($leave->responsible_spv); //dd($responsible_spv);

        $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
        $leave_modification_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        $modification = '';
        $rejection = '';
        $approval_message = '';
        $user_role = auth()->user()->role_id;

        if ($leave->status == 10){ } // do nothing

        if ($leave->status == 20){ // waiting for hrm approval
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'spv')->latest()->first();
            $comments = $approval->comments;
        }

        if ($leave->status == 30){ // waiting for md approval
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'hrm')->latest()->first();
            $comments = $approval->comments;
        }

        if ($leave->status == 40 || $leave->status == 50){ // approved-waiting payment or approved
            $approval = LeaveApproval::where('leave_id', '=', $leave_id)
                ->where('level', '=', 'md')->latest()->first();
            $comments = $approval->comments;

            //get approval message (the approval message will take into consideration all changes made by spv and hrm)
            $approval_message = $this->get_approval_message($leave); //dd($approval_message);

        }

        if ($leave->status == 99){ //rejected
            $rejection = LeaveReject::where('leave_id', '=', $leave_id)->latest()->first();
        }

        $planned_leave = LeavePlan::check_if_requested_leave_is_planned($leave);

        //filter leave types according to gender
        $unfiltered_leave_types = $leave_types;
        if( $leave->staff->gender == 'Male'){ unset($leave_types['maternity_leave']);  unset($leave_types['maternity_leave_2']);}
        elseif( $leave->staff->gender == 'Female'){ unset($leave_types['paternity_leave']); unset($leave_types['maternity_leave_2']);};

        $holidays1 = ( Holiday::get_all_holidays_in_a_year( date('Y')) )['arrays'] ;
        $holidays2 = ( Holiday::get_all_holidays_in_a_year( date('Y')+1) )['arrays'] ;


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Information Page',
            'item_id'=> $leave->id,
            'description'=> 'Viewed Information Page Of Leave Request with ID - '.$leave->id.' In Administrative Mode',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('leaves.show_admin',
            compact( 'leave', 'leave_types','leave_statuses', 'leave_entitlement', 'leaveSupervisors',
                'responsible_spv','rejection_reason','modification', 'rejection', 'approval_message', 'user_role',
                'leave_modification_reason', 'supervisor_change_reason','comments','supervisors_mode','planned_leave',
                'unfiltered_leave_types','holidays1','holidays2','include_holidays_in_leave','include_weekends_in_leave',
                'model_name', 'controller_name', 'view_type'));

    }


    public function showLeaveStatement($leave_id)
    {

        if (Gate::denies('access',['leaves','view'])){
            abort(403, 'Access Denied');
        }


        $leave = Leave::find($leave_id);
        $leave_obj = new Leave();
        $summary = $leave_obj->get_leave_summary_for_staff($leave->staff->id,$leave->year);
        $leave_types = LeaveType::get_active_leave_types()['arrays']; //dd($leave_types);
        $leave_statuses = Leave::$leave_statuses;


        $user = auth()->user()->staff;
        $generated_by = ucwords($user->first_name.' '.$user->last_name);
        $generation_date = date('d-m-Y H:m A');

        $spv = new Staff();
        $spv_approval = LeaveApproval::where('leave_id','=',$leave_id)->where('level','=','spv')->first();

        $hrm = new Staff();
        $hrm_approval = LeaveApproval::where('leave_id','=',$leave_id)->where('level','=','hrm')->first();

        $md = new Staff();
        $md_approval = LeaveApproval::where('leave_id','=',$leave_id)->where('level','=','md')->first();

        if($leave->status == 40 || $leave->status == 50){
            if( isset($spv_approval->done_by) ){ $spv = Staff::find($spv_approval->done_by); }
            if( isset($hrm_approval->done_by) ){ $hrm = Staff::find($hrm_approval->done_by); }
            if( isset($md_approval->done_by)  ){ $md = Staff::find($md_approval->done_by); }
        }

        //dd($md_approval);


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Leave Statement',
            'item_id'=> $leave->id,
            'description'=> 'Viewed Statement Of Leave Request with ID - '.$leave->id,
            'user_id'=> auth()->user()->id,
        ];
        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('leaves.statement',
            compact( 'leave','leave_types','leave_statuses','summary','generated_by','generation_date',
                'md','md_approval', 'spv_approval','spv','hrm_approval', 'hrm'));
    }


    public function edit(Leave $leave)
    {
        //
    }


    public function update(Request $request, Leave $leave)
    {
        //
    }


    public function destroy(Leave $leave)
    {
        //
    }




    /********************** LEAVE REQUEST PROCESSING SECTION ********************/

    public function approveLeave(Request $request){

        if (Gate::denies('access',['approve_leave','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Leave Request');
        }

        $data = $this->validateApprovalRequest();
        $leave_id = $data['leave_id'];
        $comments = $data['comments'];

        return $this->approveLeaveRequest($leave_id,$comments);

    }


    public function approveLeaveRequest($leave_id,$comments = ''){

        if (Gate::denies('access',['approve_leave','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Leave Request');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave = Leave::find($leave_id);
        $leave_status = $leave->status;
        $leave_spv = $leave->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $leave_status == 10 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can approve requests
            // from staff they are supervising

            $new_leave_status = 20;//'Waiting For HRM Approval'
            $approval_level = 'spv';

            if($leave_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                //update leave status
                $leave->status = $new_leave_status;
                $leave->save();

                //record leave approval
                $approval = new LeaveApproval();
                $approval->leave_id = $leave_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                if(isset($leave->id)){
                    event(new LeaveRequestApprovedBySupervisorEvent($leave));


                    //record user activity
                    $activity = [
                        'action'=> 'Approving',
                        'item'=> 'Leave Request',
                        'item_id'=> $leave->id,
                        'description'=> 'Approved Leave Request with ID - '.$leave->id.' as Supervisor',
                        'user_id'=> auth()->user()->id,
                    ];

                    $activity_category = 'major';
                    UserActivity::record_user_activity($activity, $activity_category);

                }

                $message = 'Leave request is Approved successfully.';


                return redirect('leave_admin/'.$leave_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to approve this request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $leave_status == 20){ //for HRM //Waiting For HRM Approval


            $approval_level = 'hrm';
            if( $leave->payment == 'Include'){
                $new_leave_status = 30;//'Waiting For MD Approval'
                //if hrm there is no other approval level this status will change to
                // 40 - Approved & Waiting For Payment
            }
            else{
                $new_leave_status = 30;//'Waiting For MD Approval'
                //if there is no other approval level this will change to 50 - approved
            }

            //update leave status
            $leave->status = $new_leave_status;
            $leave->save();

            //record leave approval
            $approval = new LeaveApproval();
            $approval->leave_id = $leave_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            if(isset($leave->id)){
                event(new LeaveRequestApprovedByHRMEvent($leave));


                //record user activity
                $activity = [
                    'action'=> 'Approving',
                    'item'=> 'Leave Request',
                    'item_id'=> $leave->id,
                    'description'=> 'Approved Leave Request with ID - '.$leave->id.' as Human Resource Manager',
                    'user_id'=> auth()->user()->id,
                ];

                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

            }


            $message = 'Leave request is Approved successfully.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);


        }

        if($current_user_role == 2 && $leave_status == 30){ //for MD //Waiting For MD Approval


            $approval_level = 'md';
            if( $leave->payment == 'Include' ){
                $new_leave_status = 40;//'Approved & Waiting For Payment'
            }
            else{
                $new_leave_status = 50;//'Approved'
            }

            //update leave status
            $leave->status = $new_leave_status; //Waiting For HRM Approval
            $leave->save();

            //record leave approval
            $approval = new LeaveApproval();
            $approval->leave_id = $leave_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            if(isset($leave->id)){
                event(new LeaveRequestApprovedByMDEvent($leave));


                //record user activity
                $activity = [
                    'action'=> 'Approving',
                    'item'=> 'Leave Request',
                    'item_id'=> $leave->id,
                    'description'=> 'Approved Leave Request with ID - '.$leave->id.' as Managing Director',
                    'user_id'=> auth()->user()->id,
                ];

                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

            }


            //check if it is a planned leave then mark it as taken
            $planned_leave = LeavePlan::check_if_requested_leave_is_planned($leave);

            if($planned_leave != null ){
                $planned_leave->status = 'Taken';
                $planned_leave->save();
            }


            $message = 'Leave request is Approved successfully.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);



        }

        elseif($current_user_role == 1){ //for Super Administrator


            if($leave_status == 10){ //Waiting For Supervisor Approval

                $new_leave_status = 20;//'Waiting For HRM Approval'
                $approval_level = 'spv';

                //update leave status
                $leave->status = $new_leave_status; //Waiting For HRM Approval
                $leave->save();

                //record leave approval
                $approval = new LeaveApproval();
                $approval->leave_id = $leave_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification to  new supervisor

                $message = 'Leave request is Approved successfully.';
            }


            elseif($leave_status == 20){ //Waiting For HRM Approval

                $approval_level = 'hrm';
                if($leave->type == 'annual-leave' && $leave->payment == 'Include'){
                    $new_leave_status = 30;
                }
                else{
                    $new_leave_status = 30;
                }

                //update leave status
                $leave->status = $new_leave_status; //Waiting For HRM Approval
                $leave->save();

                //record leave approval
                $approval = new LeaveApproval();
                $approval->leave_id = $leave_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification to  new supervisor

                $message = 'Leave request is Approved successfully.';
            }


            elseif($leave_status == 30){ //Waiting For MD Approval

                $approval_level = 'md';
                if( $leave->payment == 'Include' ){
                    $new_leave_status = 40;
                }
                else{
                    $new_leave_status = 50;//approved
                }

                //update leave status
                $leave->status = $new_leave_status; //Waiting For HRM Approval
                $leave->save();

                //record leave approval
                $approval = new LeaveApproval();
                $approval->leave_id = $leave_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification to  new supervisor

                $message = 'Leave request is Approved successfully.';
            }


            elseif($leave_status == 50){ //Approved

                $message = 'This Leave Request Have Already Been Approved.';

            }

            else{

                $message = 'You are not allowed to approve this Leave Request.';

            }


            return redirect('leave_admin/'.$leave_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to approve this request.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }

    }


    public function modifyApproveLeave(Request $request){

        if (Gate::denies('access',['modify_approve_leave','edit'])){
            abort(403, 'You Are Not Allowed To Modify This Leave Request');
        }

        //get and validate modified leave data
        $data = $this->validateModifyApproveRequest();
        $leave_id = $data['leave_id'];
        $new_leave_type = $data['leave_type'];
        $new_starting_date = $data['starting_date'];
        $new_ending_date = $data['ending_date'];
        $new_leave_payment = count($data['include_payment']) == 2 ? 'Include' : 'Do Not Include';
        $reason = $data['reason'];

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        //get original leave information
        $leave = Leave::find($leave_id);
        $leave_status = $leave->status;
        $leave_spv = $leave->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $leave_status == 10 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can approve requests
            // from staff they are supervising

            if($leave_spv == $current_user_staff_id){ //modify & approve only if the current user is the supervisor assigned to approve the request
                $modification_level = 'spv';
                return $this->modifyApproveLeaveRequest($leave_id,$modification_level,$new_leave_type,$new_starting_date,$new_ending_date,$new_leave_payment,$reason);
            }
            else{

                $message = 'You are not authorised to modify & approve this request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }

        }

        if($current_user_role == 3){ //for HRM

            if($leave_status = 20){ //modify & approve only if leave status is waiting for hrm approval
                $modification_level = 'hrm';
                return $this->modifyApproveLeaveRequest($leave_id,$modification_level,$new_leave_type,$new_starting_date,$new_ending_date,$new_leave_payment,$reason);
            }
            else{

                $message = 'You are not authorised to modify & approve this request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }


        }

        if($current_user_role == 2){ //for MD

            if($leave_status = 30){ //modify & approve only if leave status is waiting for md approval
                $modification_level = 'md';
                return $this->modifyApproveLeaveRequest($leave_id,$modification_level,$new_leave_type,$new_starting_date,$new_ending_date,$new_leave_payment,$reason);
            }
            else{

                $message = 'You are not authorised to modify & approve this request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }


        }

        if($current_user_role == 1){ //for Super Administrator

            $modification_level = 'adm';
            return $this->modifyApproveLeaveRequest($leave_id,$modification_level,$new_leave_type,$new_starting_date,$new_ending_date,$new_leave_payment,$reason);

        }

        else{

            $message = 'You are not Authorized to modify & approve this request.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }

    }


    public function modifyApproveLeaveRequest($leave_id,$modification_level,$new_leave_type,$new_starting_date,$new_ending_date,$new_leave_payment,$reason){

        $leave = Leave::find($leave_id);
        $current_user_staff_id = auth()->user()->staff->id;

        $original_leave_type = $leave->type;
        $original_starting_date = $leave->starting_date;
        $original_ending_date = $leave->ending_date;
        $original_leave_payment = $leave->payment;

        $new_starting_date = MyFunctions::convert_date_to_mysql_format($new_starting_date);
        $new_ending_date =   MyFunctions::convert_date_to_mysql_format($new_ending_date);

        //modify leave
        $leave->type = $new_leave_type;
        $leave->starting_date = $new_starting_date;
        $leave->ending_date =   $new_ending_date;
        $leave->payment = $new_leave_payment;
        $modifier_rank = '';

        if ($modification_level == 'spv'){ $leave->modified_by_spv = 'yes'; $modifier_rank = 'Supervisor';}
        if ($modification_level == 'hrm'){ $leave->modified_by_hrm = 'yes'; $modifier_rank = 'Human Resource Manager';}
        if ($modification_level == 'adm'){ $leave->modified_by_adm = 'yes'; $modifier_rank = 'Administrator';}

        $leave->save();

        //record leave modification
        $modification = new LeaveModification();
        $modification->leave_id = $leave_id;
        $modification->done_by = $current_user_staff_id;
        $modification->level = $modification_level;
        $modification->leave_type_changed = $original_leave_type == $new_leave_type ? 'no' : 'yes';
        $modification->original_leave_type = $original_leave_type;
        $modification->new_leave_type = $new_leave_type;
        $modification->starting_date_changed = $original_starting_date == $new_starting_date ? 'no' : 'yes';
        $modification->original_starting_date = $original_starting_date;
        $modification->new_starting_date = $new_starting_date;
        $modification->ending_date_changed = $original_ending_date == $new_ending_date ? 'no' : 'yes';
        $modification->original_ending_date = $original_ending_date;
        $modification->new_ending_date = $new_ending_date;
        $modification->leave_payment_changed = $original_leave_payment == $new_leave_payment ? 'no' : 'yes';
        $modification->original_leave_payment = $original_leave_payment;
        $modification->new_leave_payment = $new_leave_payment;
        $modification->reason = $reason;
        $modification->save();


        //record user activity
        $activity = [
            'action'=> 'Modifying & Approving',
            'item'=> 'Leave Request',
            'item_id'=> $leave->id,
            'description'=> 'Modified & Approved Leave Request with ID - '.$leave->id.' as '.$modifier_rank,
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        //approve leave
        $comments = '';
        return $this->approveLeaveRequest($leave_id,$comments);

    }


    public function changeSupervisor(Request $request){

        if (Gate::denies('access',['change_supervisor','edit'])){
            abort(403, 'You Are Not Allowed To Change Supervisor For This Leave Request');
        }

        $data = $this->validateChangeSupervisorRequest();
        $leave_id = $data['leave_id'];
        $new_spv = $data['responsible_spv'];
        $reason_for_change = $data['reason'];

        //get old supervisor
        $leave = Leave::find($leave_id);
        $old_spv = $leave->responsible_spv;

        //change supervisor, but when you change supervisor also reverse status to previous stage
        try{
            $leave->responsible_spv = $new_spv;
            $leave->status = '10';
            $leave->save();

            $changed_by = auth()->user()->staff->id;

            //record this change
            $spv_change = new LeaveChangedSupervisor();
            $spv_change->leave_id = $leave_id;
            $spv_change->old_spv_id = $old_spv;
            $spv_change->new_spv_id = $new_spv;
            $spv_change->changed_by = $changed_by;
            $spv_change->reason = $reason_for_change;
            $spv_change->save();

            //send email notification to  new supervisor

            $message = 'Supervisor Have been Changed Successfully';


            //record user activity
            $activity = [
                'action'=> 'Changing Leave Supervisor',
                'item'=> 'Leave Request',
                'item_id'=> $leave->id,
                'description'=> 'Changed Supervisor For Leave Request with ID - '.$leave->id,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);


            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }catch(\Exception $e){
            echo $e->getMessage();
        };

    }


    public function rejectLeave(Request $request){

        if (Gate::denies('access',['reject_leave','edit'])){
            abort(403, 'You Are Not Allowed To Reject This Leave Request');
        }

        $data = $this->validateRejectRequest();
        $leave_id= $data['leave_id'];
        $rejection_reason = $data['rejection_reason'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave = Leave::find($leave_id);
        $leave_status = $leave->status;
        $leave_spv = $leave->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $leave_status == 10 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can reject requests
            // from staff they are supervising

            if($leave_spv == $current_user_staff_id){ //reject only if the current user is the supervisor assigned to approve the request

                $new_leave_status = 99;//'Rejected'
                $reject_level = 'spv';

                //update leave status
                $leave->status = $new_leave_status;
                $leave->save();

                //record leave reject
                $reject = new LeaveReject();
                $reject->leave_id = $leave_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                if(isset($leave->id)){
                    event(new LeaveRequestRejectedEvent($reject_level,$leave));


                    //record user activity
                    $activity = [
                        'action'=> 'Rejecting Leave Request',
                        'item'=> 'Leave Request',
                        'item_id'=> $leave->id,
                        'description'=> 'Rejected Leave Request with ID - '.$leave->id.' as Supervisor',
                        'user_id'=> auth()->user()->id,
                    ];

                    $activity_category = 'major';
                    UserActivity::record_user_activity($activity, $activity_category);

                }

                $message = 'Leave Request Rejected Successfully.';


                return redirect('leave_admin/'.$leave_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Reject this Leave Request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $leave_status == 20){ //for HRM

            $reject_level = 'hrm';
            $new_leave_status = 99;//'Rejected For Correction'

            //update leave status
            $leave->status = $new_leave_status;
            $leave->save();

            //record leave rejection
            $reject = new LeaveReject();
            $reject->leave_id = $leave_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            if(isset($leave->id)){
                event(new LeaveRequestRejectedEvent($reject_level,$leave));


                //record user activity
                $activity = [
                    'action'=> 'Rejecting Leave Request',
                    'item'=> 'Leave Request',
                    'item_id'=> $leave->id,
                    'description'=> 'Rejected Leave Request with ID - '.$leave->id.' as Human Resource Manager',
                    'user_id'=> auth()->user()->id,
                ];

                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

            }


            $message = 'Leave Request Rejected Successfully.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);



        }

        if($current_user_role == 2 && $leave_status == 30){ //for MD

            $reject_level = 'md';
            $new_leave_status = 99;//'Rejected For Correction'

            //update leave status
            $leave->status = $new_leave_status;
            $leave->save();

            //record leave rejection
            $reject = new LeaveReject();
            $reject->leave_id = $leave_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            if(isset($leave->id)){
                event(new LeaveRequestRejectedEvent($reject_level,$leave));


                //record user activity
                $activity = [
                    'action'=> 'Rejecting Leave Request',
                    'item'=> 'Leave Request',
                    'item_id'=> $leave->id,
                    'description'=> 'Rejected Leave Request with ID - '.$leave->id.' as Managing Director',
                    'user_id'=> auth()->user()->id,
                ];

                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);
            }


            $message = 'Leave Request Rejected Successfully.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }

        if($current_user_role == 1){ //for Super Administrator


            if($leave_status == 10 || $leave_status == 20 || $leave_status == 30 || $leave_status == 40 || $leave_status == 50){
                //can reject leave request at any status

                $new_leave_status = 99;
                $reject_level = 'adm';

                //update leave status
                $leave->status = $new_leave_status;
                $leave->save();

                //record leave rejection
                $reject = new LeaveReject();
                $reject->leave_id = $leave_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                if(isset($leave->id)){
                    event(new LeaveRequestRejectedEvent($reject_level,$leave));


                    //record user activity
                    $activity = [
                        'action'=> 'Rejecting Leave Request',
                        'item'=> 'Leave Request',
                        'item_id'=> $leave->id,
                        'description'=> 'Rejected Leave Request with ID - '.$leave->id.' as Administrator',
                        'user_id'=> auth()->user()->id,
                    ];

                    $activity_category = 'major';
                    UserActivity::record_user_activity($activity, $activity_category);
                }

                $message = 'Leave Request Rejected successfully.';

            }

            else{

                $message = 'You are not allowed to reject this Leave Request.';
            }

            return redirect('leave_admin/'.$leave_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to reject this Leave Request.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }

    }


    public function confirmPayment(Request $request){

        if (Gate::denies('access',['confirm_leave_payment','edit'])){
            abort(403, 'You Are Not Allowed To Confirm Payment For This Leave Request');
        }

        $data = $this->validatePaymentRequest();
        $leave_id = $data['leave_id'];
        $comments = $data['comments'];


        return $this->confirmLeavePayment($leave_id,$comments);

    }


    public function confirmLeavePayment($leave_id,$comments = ''){

        if (Gate::denies('access',['confirm_leave_payment','edit'])){
            abort(403, 'You Are Not Allowed To Confirm Payment For This Leave Request');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave = Leave::find($leave_id);
        $leave_status = $leave->status;


        if($current_user_role == 1 || $current_user_role == 2 || $current_user_role == 3 || $current_user_role == 4 ){

            if($leave_status == 40){ //Approved & Waiting Payment

                $new_leave_status = 50;//'approved'

                //update leave status
                $leave->status = $new_leave_status;
                $leave->paid_by_accountant = 'yes';
                $leave->save();

                //record payment confirmation
                $payment = new LeavePayment();
                $payment->leave_id = $leave_id;
                $payment->confirmed_by = $current_user_staff_id;
                $payment->comments = $comments;
                $payment->save();

                //send email notifications
                if(isset($leave->id)){
                    event(new LeavePaymentConfirmedEvent($leave));


                    //record user activity
                    $activity = [
                        'action'=> 'Leave Payment Confirmation',
                        'item'=> 'Leave Request',
                        'item_id'=> $leave->id,
                        'description'=> 'Confirmed Payment For Leave Request with ID - '.$leave->id,
                        'user_id'=> auth()->user()->id,
                    ];

                    $activity_category = 'major';
                    UserActivity::record_user_activity($activity, $activity_category);


                }

                $message = 'Leave Payment Confirmed successfully.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }

            else{

                $message = 'You are not authorised to confirm payment for this Leave Request.';

                return redirect('leave_admin/'.$leave_id)->with('message',$message);

            }

        }

        else{

            $message = 'You are not Authorized to confirm payment for this Leave Request.';

            return redirect('leave_admin/'.$leave_id)->with('message',$message);

        }

    }


    public function get_approval_message($leave){

        $approval_message = '';

        if($leave->modified_by_spv == 'no' && $leave->modified_by_hrm == 'no'){
            $approval_message  = 'Hello, your Leave Request have been approved by Human Resource Manager & Managing Director, your leave starts on ';
            $approval_message .=  date('d-m-Y',strtotime($leave->starting_date)).' and will end on '.date('d-m-Y',strtotime($leave->ending_date));
            $approval_message .= ', please enjoy your leave.';
        }

        if( ($leave->modified_by_spv == 'yes' && $leave->modified_by_hrm == 'no') || ($leave->modified_by_spv == 'no' && $leave->modified_by_hrm == 'yes') ){

            $modification = new LeaveModification();
            $modified_by = '';

            if($leave->modified_by_spv == 'yes'){
                $modification = LeaveModification::where('leave_id', '=', $leave->id)
                    ->where('level', '=', 'spv')->latest()->first();
                $modified_by = 'your Supervisor';
            }

            if($leave->modified_by_hrm == 'yes'){
                $modification = LeaveModification::where('leave_id', '=', $leave->id)
                    ->where('level', '=', 'hrm')->latest()->first();
                $modified_by = 'HRM';
            }

            $starting_date = $leave->starting_date;
            $ending_date = $leave->ending_date;

            $leave_type_msg = '';
            if($modification->leave_type_changed == 'yes'){
                $leave_type_msg  = "<br> - Leave type was changed";
                $leave_type_msg .= " from ".Leave::$leave_types[$modification->original_leave_type];
                $leave_type_msg .= " to ".Leave::$leave_types[$modification->new_leave_type];
            }

            $starting_date_msg = '';
            if($modification->starting_date_changed == 'yes'){
                $starting_date_msg  = "<br> - Leave starting date was changed";
                $starting_date_msg .= " from ".date('d-m-Y',strtotime($modification->original_starting_date));
                $starting_date_msg .= " to ".date('d-m-Y',strtotime($modification->new_starting_date));

                $starting_date = $modification->new_starting_date;
            }

            $ending_date_msg = '';
            if($modification->ending_date_changed == 'yes'){
                $ending_date_msg  = "<br> - Leave ending date was changed";
                $ending_date_msg .= " from ".date('d-m-Y',strtotime($modification->original_ending_date));
                $ending_date_msg .= " to ".date('d-m-Y',strtotime($modification->new_ending_date));

                $ending_date = $modification->new_ending_date;
            }

            $payment_msg = '';
            if($modification->leave_payment_changed == 'yes'){

                if($modification->new_leave_payment == 'Include'){
                    $payment_msg  = "<br> - Payment will be issued for this leave";
                }else{
                    $payment_msg  = "<br> - No Payment will be issued for this leave";
                }
            }

            $approval_message  = "Hello, your Leave Request have been approved by Human Resource Manager & Managing Director, your leave starts on ";
            $approval_message .=  date('d-m-Y',strtotime($starting_date))." and will end on ".date('d-m-Y',strtotime($ending_date));
            $approval_message .= ". However there were changes made to your leave request by ".$modified_by;
            $approval_message .= ". The changes are as mentioned below :-";
            $approval_message .= $leave_type_msg.$starting_date_msg.$ending_date_msg.$payment_msg;
            $approval_message .= "<br><br>".ucwords($modified_by)." Reason For These Modification :-";
            $approval_message .= "<br> - ".$modification->reason;


        }

        if($leave->modified_by_spv == 'yes' && $leave->modified_by_hrm == 'yes'){

            $modification1 = LeaveModification::where('leave_id', '=', $leave->id)
                ->where('level', '=', 'spv')->latest()->first();
            $modification2 = LeaveModification::where('leave_id', '=', $leave->id)
                ->where('level', '=', 'hrm')->latest()->first();

            $starting_date = $leave->starting_date;
            $ending_date = $leave->ending_date;

            $leave_type_msg = '';
            if($modification1->leave_type_changed == 'yes' && $modification2->leave_type_changed == 'no'){
                $leave_type_msg  = "<br> - Leave type was changed";
                $leave_type_msg .= " from ".Leave::$leave_types[$modification1->original_leave_type];
                $leave_type_msg .= " to ".Leave::$leave_types[$modification1->new_leave_type];
            }
            if($modification1->leave_type_changed == 'no' && $modification2->leave_type_changed == 'yes'){
                $leave_type_msg  = "<br> - Leave type was changed";
                $leave_type_msg .= " from ".Leave::$leave_types[$modification2->original_leave_type];
                $leave_type_msg .= " to ".Leave::$leave_types[$modification2->new_leave_type];
            }
            if($modification1->leave_type_changed == 'yes' && $modification2->leave_type_changed == 'yes'){
                $leave_type_msg  = "<br> - Leave type was changed";
                $leave_type_msg .= " from ".Leave::$leave_types[$modification1->original_leave_type];
                $leave_type_msg .= " to ".Leave::$leave_types[$modification1->new_leave_type]." by Supervisor.";
                $leave_type_msg .= " Then it was changed";
                $leave_type_msg .= " from ".Leave::$leave_types[$modification2->original_leave_type];
                $leave_type_msg .= " to ".Leave::$leave_types[$modification2->new_leave_type]." by HRM.";
            }




            $starting_date_msg = '';
            if($modification1->starting_date_changed == "yes" && $modification2->starting_date_changed == "no"){
                $starting_date_msg  = "<br> - Leave starting date was changed";
                $starting_date_msg .= " from ".date('d-m-Y',strtotime($modification1->original_starting_date));
                $starting_date_msg .= " to ".date('d-m-Y',strtotime($modification1->new_starting_date));

                $starting_date = $modification1->new_starting_date;
            }
            if($modification1->starting_date_changed == "no" && $modification2->starting_date_changed == "yes"){
                $starting_date_msg  = "<br> - Leave starting date was changed";
                $starting_date_msg .= " from ".date('d-m-Y',strtotime($modification2->original_starting_date));
                $starting_date_msg .= " to ".date('d-m-Y',strtotime($modification2->new_starting_date));

                $starting_date = $modification2->new_starting_date;
            }
            if($modification1->starting_date_changed == "yes" && $modification2->starting_date_changed == "yes"){
                $starting_date_msg  = "<br> - Leave starting date was changed";
                $starting_date_msg .= " from ".date('d-m-Y',strtotime($modification1->original_starting_date));
                $starting_date_msg .= " to ".date('d-m-Y',strtotime($modification1->new_starting_date))." by Supervisor.";
                $starting_date_msg .= " Then it was changed";
                $starting_date_msg .= " from ".date('d-m-Y',strtotime($modification2->original_starting_date));
                $starting_date_msg .= " to ".date('d-m-Y',strtotime($modification2->new_starting_date))." by HRM.";

                $starting_date = $modification2->new_starting_date;
            }



            $ending_date_msg = "";
            if($modification1->ending_date_changed == "yes" && $modification2->ending_date_changed == "no"){
                $ending_date_msg  = "<br> - Leave ending date was changed";
                $ending_date_msg .= " from ".date('d-m-Y',strtotime($modification1->original_ending_date));
                $ending_date_msg .= " to ".date('d-m-Y',strtotime($modification1->new_ending_date));

                $ending_date = $modification1->new_ending_date;
            }
            if($modification1->ending_date_changed == "no" && $modification2->ending_date_changed == "yes"){
                $ending_date_msg  = "<br> - Leave ending date was changed";
                $ending_date_msg .= " from ".date('d-m-Y',strtotime($modification2->original_ending_date));
                $ending_date_msg .= " to ".date('d-m-Y',strtotime($modification2->new_ending_date));

                $ending_date = $modification2->new_ending_date;
            }
            if($modification1->ending_date_changed == "yes" && $modification2->ending_date_changed == "yes"){
                $ending_date_msg  = "<br> - Leave ending date was changed";
                $ending_date_msg .= " from ".date('d-m-Y',strtotime($modification1->original_ending_date));
                $ending_date_msg .= " to ".date('d-m-Y',strtotime($modification1->new_ending_date))." by Supervisor.";
                $ending_date_msg .= " Then it was changed";
                $ending_date_msg .= " from ".date('d-m-Y',strtotime($modification2->original_ending_date));
                $ending_date_msg .= " to ".date('d-m-Y',strtotime($modification2->new_ending_date))." by HRM.";

                $ending_date = $modification2->new_ending_date;
            }
            //dd($modification1->ending_date_changed.' | '.$modification2->ending_date_changed  );




            $payment_msg = "";
            if($modification1->leave_payment_changed == "yes" && $modification2->leave_payment_changed == "no"){

                if($modification1->new_leave_payment == "Include"){
                    $payment_msg  = "<br> - Payment will be issued for this leave";
                }else{
                    $payment_msg  = "<br> - No Payment will be issued for this leave";
                }
            }
            if($modification1->leave_payment_changed == "no" && $modification2->leave_payment_changed == "yes"){

                if($modification2->new_leave_payment == "Include"){
                    $payment_msg  = "<br> - Payment will be issued for this leave";
                }else{
                    $payment_msg  = "<br> - No Payment will be issued for this leave";
                }
            }
            if($modification1->leave_payment_changed == "yes" && $modification2->leave_payment_changed == "yes"){

                if($modification2->new_leave_payment == "Include"){
                    $payment_msg  = "<br> - Payment will be issued for this leave";
                }else{
                    $payment_msg  = "<br> - No Payment will be issued for this leave";
                }
            }

            $approval_message  = "Hello, your Leave Request have been approved by Human Resource Manager & Managing Director, your leave starts on ";
            $approval_message .=  date('d-m-Y',strtotime($starting_date))." and will end on ".date('d-m-Y',strtotime($ending_date));
            $approval_message .= ". However there were changes made to your leave request by your Supervisor & Human Resource Manager.";
            $approval_message .= " The changes are as mentioned below :-";
            $approval_message .= $leave_type_msg.$starting_date_msg.$ending_date_msg.$payment_msg;
            $approval_message .= "<br><br> Supervisor Reason For These Modification :-";
            $approval_message .= "<br> - ".$modification1->reason;
            $approval_message .= "<br><br> HRM Reason For These Modification :-";
            $approval_message .= "<br> - ".$modification2->reason;

        }

        return $approval_message;

    }




    /************************* VALIDATION SECTION **************************/


    public function validateLeaveRequest(){

        return  request()->validate([
            'leave_type' =>  'required',
            'include_payment' =>  'nullable',
            'starting_date' =>  'required',
            'ending_date' =>  'required',
            'responsible_spv' =>  'required',
            'description' =>  'nullable',
        ]);


    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'reason' =>  'required',
            'leave_id' => 'required',
        ]);

    }


    public function validateApprovalRequest(){

        return  request()->validate([
            'leave_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }


    public function validatePaymentRequest(){

        return  request()->validate([
            'leave_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }


    public function validateModifyApproveRequest(){

        return  request()->validate([
            'leave_id' => 'required',
            'leave_type' =>  'required',
            'starting_date' =>  'required',
            'ending_date' =>  'required',
            'include_payment' =>  'required',
            'reason' =>  'required',
        ]);

    }


    public function validateRejectRequest(){

        return  request()->validate([
            'leave_id' => 'required',
            'rejection_reason' =>  'required',
        ]);

    }



    /******************* AJAX REQUESTS ****************/

    public function ajaxCheckDate(Request $request)
    {

        $leave_id ='0';

        $staff_id = $request->staff_id;
        $year = $request->year;
        $leave_date = $request->leave_date;
        $leave_date = MyFunctions::convert_date_to_mysql_format($leave_date);

        if($request->ajax())
        {
            $leave = DB::table('leaves')
                ->where('employee_id', '=',$staff_id)
                ->where('year', '=',$year)
                ->where('starting_date', '<=',$leave_date)
                ->where('ending_date', '>=',$leave_date)
                ->first();

            if( isset($leave->id) ){
                $leave_id = $leave->id;
            }
        }

        echo json_encode($leave_id);

    }



    /******************************** OTHER FUNCTIONS ***********************/


    public function viewDocument($filename){
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "\\app\\public\\leave_supporting_documents\\" . $filename;
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

    public function downloadDocument($filename){
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "\\app\\public\\leave_supporting_documents\\" . $filename;
        //dd($file_path);
        $headers = array(
            'Content-Type' => 'application/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        );
        if ( file_exists( $file_path ) ) {
            // Send Download
            return \Response::download( $file_path, $filename, $headers );
        } else {
            // Error
            exit( 'Requested file does not exist on our server!' );
        }

    }



}
