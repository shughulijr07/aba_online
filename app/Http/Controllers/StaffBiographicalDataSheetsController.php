<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Region;
use App\Models\Staff;
use App\Models\StaffBiographicalDataSheet;
use App\Models\StaffJobTitle;
use App\Models\SystemRole;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Console\Helper\Table;


class StaffBiographicalDataSheetsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','store'])){
            abort(403, 'Access Denied');
        }

        $staff = auth()->user()->staff;
        $staff_biographical_data_sheet = new StaffBiographicalDataSheet();
        $departments = Department::all();
        $job_titles = StaffJobTitle::all();
        $regions = Region::all();
        $system_roles = SystemRole::get_system_roles_list();
        $mms_access = 'no';
        $role_id = '';
        $supervisors = Staff::get_supervisors();
        $supervisor_id = '';

        $education = [];
        $language = [];
        $employment_history = [];
        $specific_consultant_services = [];
        $dependents = [];
        $emergency_contacts = [];


        $department_id = '';
        $job_title_id = '';
        $gender = '';
        $staff_status = '';
        $duty_station = '';

        $model_name = 'staff_biographical_data_sheet';
        $controller_name = 'staff_biographical_data_sheets';
        $view_type = 'create';



        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'BIODATA Page',
            'item_id'=> '',
            'description'=> 'Viewed Staff Biographical Data Recording Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.biographical_data_sheets.create',
            compact( 'staff','staff_biographical_data_sheet','system_roles', 'mms_access', 'role_id',
                'departments','job_titles', 'regions', 'supervisors', 'supervisor_id',
                'department_id', 'job_title_id', 'gender','staff_status', 'duty_station',
                'education', 'language','employment_history', 'specific_consultant_services','dependents',
                'emergency_contacts',
                'model_name', 'controller_name', 'view_type'));

    }


    public function createForStaff($staff_id)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','store'])){
            abort(403, 'Access Denied');
        }

        $staff = Staff::find($staff_id);
        $staff_biographical_data_sheet = new StaffBiographicalDataSheet();

        $education = [];
        $language = [];
        $employment_history = [];
        $specific_consultant_services = [];
        $dependents = [];
        $emergency_contacts = [];

        $gender = '';

        $model_name = 'staff_biographical_data_sheet';
        $controller_name = 'staff_biographical_data_sheets';
        $view_type = 'create';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'BIODATA Page',
            'item_id'=> '',
            'description'=> 'Viewed Staff Biographical Data Recording Page In Administrative Mode',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.biographical_data_sheets.create',
            compact( 'staff','staff_biographical_data_sheet', 'gender',
                'education', 'language','employment_history', 'specific_consultant_services','dependents','emergency_contacts',
                'model_name', 'controller_name', 'view_type'));

    }




    public function store(Request $request)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','store'])){
            abort(403, 'Access Denied');
        }

        $all = $request->all();
        $data = [];
        $data_arrays = [];

        if( in_array(auth()->user()->role_id, [1,3])){
            $data = $this->validateRequest();
            $data_arrays = array_slice($all, 21);
        }else{
            $data = $this->validateRequest2();
            $data_arrays = array_slice($all, 13);
        }


        // get data with arrays

        $education = [];
        $language = [];
        $employment_history = [];
        $consultant_services = [];

        foreach ($data_arrays as $key=>$contents){

            if( strpos($key,'education_table_row') !== false){
                $education[$key] =$contents;
            }
            elseif( strpos($key,'language_table_row') !== false){
                $language[$key] =$contents;
            }
            elseif( strpos($key,'employment_history_table_row') !== false){
                $employment_history[$key] =$contents;
            }
            elseif( strpos($key,'consultant_service_table_row') !== false){
                $consultant_services[$key] =$contents;
            }

        }



        $staff_biodata = new StaffBiographicalDataSheet();
        $staff_biodata->staff_id = $data['staff_id'];
        $staff_biodata->first_name =  $data['first_name'];
        $staff_biodata->middle_name =  $data['middle_name'];
        $staff_biodata->last_name =  $data['last_name'];
        $staff_biodata->gender =  $data['gender'];
        $staff_biodata->address =  $data['address'];


        //normal staff cannot fill the contractor's part
        if( in_array(auth()->user()->role_id, [1,3])){
            $staff_biodata->contractor_name =  $data['contractor_name'];
            $staff_biodata->contractor_no =  $data['contractor_no'];
            $staff_biodata->position_under_contract =  $data['position_under_contract'];
            $staff_biodata->proposed_salary_lcy =  $data['proposed_salary_lcy'];
            $staff_biodata->proposed_salary_frc =  $data['proposed_salary_frc'];
            $staff_biodata->assigned_country =  $data['assigned_country'];
            $staff_biodata->employment_duration =  $data['employment_duration'];
            $staff_biodata->date_of_employment =  $data['date_of_employment'];
        }

        $staff_biodata->phone_no =  $data['phone_no'];
        $staff_biodata->place_of_birth =  $data['place_of_birth'];
        $staff_biodata->date_of_birth =  $data['date_of_birth'];
        $staff_biodata->citizenship =  $data['citizenship'];
        $staff_biodata->home_region =  $data['home_region'];
        $staff_biodata->home_district =  $data['home_district'];

        $staff_biodata->education = json_encode($education);
        $staff_biodata->language_proficiency = json_encode($language);
        $staff_biodata->employment_history = json_encode($employment_history);
        $staff_biodata->specific_consultant_services = json_encode($consultant_services);

        $staff_biodata->net_salary = '';
        $staff_biodata->gross_salary = '';
        $staff_biodata->employment_period_salary = json_encode([]);

        $staff_biodata->certification = '';
        $staff_biodata->certification_date = date('Y-m-d');
        $staff_biodata->employee_signature = '';
        $staff_biodata->contractor_certification = '';
        $staff_biodata->contractor_certification_date = date('Y-m-d');
        $staff_biodata->contractor_signature = '';
        $staff_biodata->status = '10';

        $staff_biodata->save();


        //update staff information
        $staff = Staff::find($data['staff_id']);

        if(isset($staff->id)){

            if( $data['first_name'] != '' ){ $staff->first_name =  $data['first_name']; }
            if( $data['middle_name'] != '' ){ $staff->middle_name =  $data['middle_name']; }
            if( $data['last_name'] != '' ){ $staff->last_name =  $data['last_name']; }
            if( $data['gender'] != '' ){ $staff->gender =  $data['gender']; }
            if( $data['address'] != '' ){ $staff->home_address =  $data['address']; }
            if( $data['phone_no'] != '' ){ $staff->phone_no =  $data['phone_no']; }
            if( $data['place_of_birth'] != '' ){ $staff->place_of_birth =  $data['place_of_birth']; }
            if( $data['date_of_birth'] != '' ){ $staff->date_of_birth =  $data['date_of_birth']; }

            //these are those values which are to be added by HRM only
            if( in_array(auth()->user()->role_id, [1,3])){
                if( $data['date_of_employment'] != '' ){ $staff->date_of_employment =  $data['date_of_employment']; }
            }

            $staff->save();

            //store Image
            $this->storeImage($staff);

        }



        if( isset($staff_biodata->id)){

            $staff = $staff_biodata->staff;
            $staff_name = ucwords($staff->first_name.' '.$staff->last_name);

            //record user activity
            $activity = [
                'action'=> 'Saving',
                'item'=> 'BIODATA Information',
                'item_id'=> $staff_biodata->id,
                'description'=> 'Saved BIODATA Information Form For '.$staff_name,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);

        }




        return redirect('staff_biographical_data_sheets/'.$staff_biodata->id);


    }



    public function show(StaffBiographicalDataSheet $staffBiographicalDataSheet)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','view'])){
            abort(403, 'Access Denied');
        }

        $staff_biographical_data_sheet = $staffBiographicalDataSheet;
        $staff = $staff_biographical_data_sheet->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $gender = $staff->gender;


        $education = json_decode($staff_biographical_data_sheet->education,true);
        $language = json_decode($staff_biographical_data_sheet->language_proficiency,true);
        $employment_history =  json_decode($staff_biographical_data_sheet->employment_history,true);
        $specific_consultant_services =  json_decode($staff_biographical_data_sheet->specific_consultant_services,true);

        //dd($employment_history);

        $model_name = 'staff_biographical_data_sheet';
        $controller_name = 'staff_biographical_data_sheets';
        $view_type = 'show';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Biodata Form',
            'item_id'=> $staff_biographical_data_sheet->id,
            'description'=> 'Viewed '.$staff_name.' \'s Biodata Form',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.biographical_data_sheets.show',
            compact( 'staff','staff_biographical_data_sheet', 'education', 'language','employment_history',
                'specific_consultant_services', 'gender',
                'model_name', 'controller_name', 'view_type'));

    }



    public function edit(StaffBiographicalDataSheet $staffBiographicalDataSheet)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','edit'])){
            abort(403, 'Access Denied');
        }

        $staff_biographical_data_sheet = $staffBiographicalDataSheet;
        $staff = $staff_biographical_data_sheet->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $gender = $staff->gender;


        $education = json_decode($staff_biographical_data_sheet->education,true);
        $language = json_decode($staff_biographical_data_sheet->language_proficiency,true);
        $employment_history =  json_decode($staff_biographical_data_sheet->employment_history,true);
        $specific_consultant_services =  json_decode($staff_biographical_data_sheet->specific_consultant_services,true);


        $model_name = 'staff_biographical_data_sheet';
        $controller_name = 'staff_biographical_data_sheets';
        $view_type = 'edit';


        //record user activity
        $activity = [
            'action'=> 'View',
            'item'=> 'Biodata Editing Form',
            'item_id'=> $staff_biographical_data_sheet->id,
            'description'=> 'Viewed '.$staff_name.' \'s Biodata Form in editing mode',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('staff.biographical_data_sheets.edit',
            compact( 'staff','staff_biographical_data_sheet', 'education', 'language','employment_history',
                'specific_consultant_services', 'gender',
                'model_name', 'controller_name', 'view_type'));

    }



    public function update(Request $request,StaffBiographicalDataSheet $staffBiographicalDataSheet)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','edit'])){
            abort(403, 'Access Denied');
        }

        $all = $request->all();
        $data = [];
        $data_arrays = [];

        if( in_array(auth()->user()->role_id, [1,3])){
            $data = $this->validateRequest();
            $data_arrays = array_slice($all, 22);
        }else{
            $data = $this->validateRequest2();
            $data_arrays = array_slice($all, 13);
        }

        $education = [];
        $language = [];
        $employment_history = [];
        $consultant_services = []; //dump($all); dd($data_arrays);

        foreach ($data_arrays as $key=>$contents){

            if( strpos($key,'education_table_row') !== false){
                $education[$key] =$contents;
            }
            elseif( strpos($key,'language_table_row') !== false){
                $language[$key] =$contents;
            }
            elseif( strpos($key,'employment_history_table_row') !== false){
                $employment_history[$key] =$contents;
            }
            elseif( strpos($key,'consultant_service_table_row') !== false){
                $consultant_services[$key] =$contents;
            }

        }



        $staff_biodata = $staffBiographicalDataSheet;
        $staff_biodata->staff_id = $data['staff_id'];
        $staff_biodata->first_name =  $data['first_name'];
        $staff_biodata->middle_name =  $data['middle_name'];
        $staff_biodata->last_name =  $data['last_name'];
        $staff_biodata->gender =  $data['gender'];
        $staff_biodata->address =  $data['address'];


        //normal staff cannot fill the contractor's part
        if( in_array(auth()->user()->role_id, [1,3])){
            $staff_biodata->contractor_name =  $data['contractor_name'];
            $staff_biodata->contractor_no =  $data['contractor_no'];
            $staff_biodata->position_under_contract =  $data['position_under_contract'];
            $staff_biodata->proposed_salary_lcy =  $data['proposed_salary_lcy'];
            $staff_biodata->proposed_salary_frc =  $data['proposed_salary_frc'];
            $staff_biodata->assigned_country =  $data['assigned_country'];
            $staff_biodata->employment_duration =  $data['employment_duration'];
            $staff_biodata->date_of_employment =  $data['date_of_employment'];
        }

        $staff_biodata->phone_no =  $data['phone_no'];
        $staff_biodata->place_of_birth =  $data['place_of_birth'];
        $staff_biodata->date_of_birth =  $data['date_of_birth'];
        $staff_biodata->citizenship =  $data['citizenship'];
        $staff_biodata->home_region =  $data['home_region'];
        $staff_biodata->home_district =  $data['home_district'];

        $staff_biodata->education = json_encode($education);
        $staff_biodata->language_proficiency = json_encode($language);
        $staff_biodata->employment_history = json_encode($employment_history);
        $staff_biodata->specific_consultant_services = json_encode($consultant_services);

        $staff_biodata->net_salary = '';
        $staff_biodata->gross_salary = '';
        $staff_biodata->employment_period_salary = json_encode([]);

        $staff_biodata->certification = '';
        $staff_biodata->certification_date = date('Y-m-d');
        $staff_biodata->employee_signature = '';
        $staff_biodata->contractor_certification = '';
        $staff_biodata->contractor_certification_date = date('Y-m-d');
        $staff_biodata->contractor_signature = '';
        $staff_biodata->status = '10';

        $staff_biodata->save();


        //update staff information
        $staff = Staff::find($data['staff_id']);

        if(isset($staff->id)){

            if( $data['first_name'] != '' ){ $staff->first_name =  $data['first_name']; }
            if( $data['middle_name'] != '' ){ $staff->middle_name =  $data['middle_name']; }
            if( $data['last_name'] != '' ){ $staff->last_name =  $data['last_name']; }
            if( $data['gender'] != '' ){ $staff->gender =  $data['gender']; }
            if( $data['address'] != '' ){ $staff->home_address =  $data['address']; }
            if( $data['phone_no'] != '' ){ $staff->phone_no =  $data['phone_no']; }
            if( $data['place_of_birth'] != '' ){ $staff->place_of_birth =  $data['place_of_birth']; }
            if( $data['date_of_birth'] != '' ){ $staff->date_of_birth =  $data['date_of_birth']; }

            //these are those values which are to be added by HRM only
            if( in_array(auth()->user()->role_id, [1,3])){
                if( $data['date_of_employment'] != '' ){ $staff->date_of_employment =  $data['date_of_employment']; }
            }


            $staff->save();

            //store Image
            $this->storeImage($staff);

        }


        //record user activity
        $staff = $staff_biodata->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Updating',
            'item'=> 'Biodata Form',
            'item_id'=> $staff_biodata->id,
            'description'=> 'Edited & Updated '.$staff_name.' \'s Biodata Form',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return redirect('staff_biographical_data_sheets/'.$staff_biodata->id);


    }


    public function destroy(StaffBiographicalDataSheet $staffBiographicalDataSheet)
    {
        if (Gate::denies('access',['staff_biographical_data_sheets','delete'])){
            abort(403, 'Access Denied');
        }

        $staff = $staffBiographicalDataSheet->staff;
        $item_id = $staffBiographicalDataSheet->id;

        $staffBiographicalDataSheet->delete();


        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Deleting',
            'item'=> 'Biodata Form',
            'item_id'=> $item_id,
            'description'=> 'Deleted '.$staff_name.' \'s Biodata Form',
            'user_id'=> auth()->user()->id,
        ];


        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return redirect('staff/'.$staff->id);
    }



    private function validateRequest(){

        return $biodata =  request()->validate([
            'staff_id' =>  'required',
            'image' =>  'sometimes|file|image|max:3000',
            'first_name' =>  'required',
            'middle_name' =>  'required',
            'last_name' =>  'required',
            'gender' =>  'required',
            'address' =>  'required',
            "phone_no" => 'required',
            "place_of_birth" => 'required',
            "date_of_birth" => 'required',
            "citizenship" => 'required',
            "home_region" => 'nullable',
            "home_district" => 'nullable',
            "contractor_name" => 'nullable',
            "contractor_no" => 'nullable',
            "position_under_contract" => 'nullable',
            "proposed_salary_lcy" => 'nullable',
            "proposed_salary_frc" => 'nullable',
            "employment_duration" => 'nullable',
            "assigned_country" => 'nullable',
            "date_of_employment" => 'nullable',
        ]);

    }



    private function validateRequest2(){

        return $biodata =  request()->validate([
            'staff_id' =>  'required',
            'image' =>  'sometimes|file|image|max:3000',
            'first_name' =>  'required',
            'middle_name' =>  'required',
            'last_name' =>  'required',
            'gender' =>  'required',
            'address' =>  'required',
            "phone_no" => 'required',
            "place_of_birth" => 'required',
            "date_of_birth" => 'required',
            "citizenship" => 'required',
            "home_region" => 'nullable',
            "home_district" => 'nullable',
        ]);

    }


    private function storeImage($staff){

        if ( request()->has('image') ){
            $staff->update([
                'image' => request()->image->store('staff_images','public'),
            ]);
        }

        if ( request()->has('signature') ){
            $staff->update([
                'signature' => request()->signature->store('staff_signatures','public'),
            ]);
        }

    }

}
