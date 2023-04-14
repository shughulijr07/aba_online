<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\GlAccount;
use App\Models\Helper;
use App\Imports\ExcelImport;
use App\Models\Region;
use App\Models\Staff;
use App\Models\StaffJobTitle;
use App\Models\StaffStatusChange;
use App\Models\Supervisor;
use App\Models\SystemRole;
use App\Models\TimeSheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;

class StaffController extends Controller
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
        if (Gate::denies('access',['staff','view'])){
            abort(403, 'Access Denied');
        }

        $staff = Staff::get_valid_staff_list();

        $model_name = 'staff';
        $controller_name = 'staff';
        $view_type = 'index';

        return view('staff.staff.index',
            compact('staff','model_name', 'controller_name', 'view_type'));

    }


    public function supervisorsIndex()
    {
        if (Gate::denies('access',['staff','view'])){
            abort(403, 'Access Denied');
        }

        $staff = Staff::get_valid_staff_list();
        $supervisors = Staff::get_supervisors();
        $supervisor = new Staff();

        $model_name = 'staff';
        $controller_name = 'staff';
        $view_type = 'index';

        return view('staff.supervisors.index',
            compact('staff','supervisors', 'supervisor','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['staff','store'])){
            abort(403, 'Access Denied');
        }

        $staff = new staff();
        $departments = Department::all();
        $job_titles = StaffJobTitle::all();
        $regions = Region::all();
        $system_roles = SystemRole::get_system_roles_list();
        $mms_access = 'no';
        $role_id = '';
        $supervisors = Staff::get_supervisors();
        $supervisor_id = '';

        $date_of_status_change = '';
        $status_change_description = '';
        $status_change_attachments = '';

        $department_id = '';
        $job_title_id = '';
        $gender = '';
        $staff_status = '';
        $duty_station = '';

        $model_name = 'staff';
        $controller_name = 'staff';
        $view_type = 'create';

        return view('staff.staff.create',
            compact( 'staff','system_roles', 'mms_access', 'role_id',
                'departments','job_titles', 'regions', 'supervisors', 'supervisor_id',
                'department_id', 'job_title_id', 'gender','staff_status', 'duty_station',
                'date_of_status_change','status_change_description','status_change_attachments',
                'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['staff','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();
        $staff_data = $data['staff_data'];
        $mms_data = $data['mms_data'];
        $staff_status_data = $data['staff_status'];

        $staff_data['date_of_termination'] = $staff_status_data['date_of_status_change'];

        //store staff
        $staff = Staff::create($staff_data);

        //update staff status
        if( $staff_status_data['staff_status'] != 'Active' &&  $staff_status_data['staff_status'] != $staff->staff_status ){

            $status_change = new StaffStatusChange();
            $status_change->staff_id = $staff->id;
            $status_change->status = $staff_status_data['staff_status'];
            $status_change->date = $staff_status_data['date_of_status_change'];
            $status_change->description = $staff_status_data['status_change_description'];
            $status_change->attachments = null;
            $status_change->save();

            //Update Status Attachments
            $this->storeStatusAttachments($status_change);
        }

        //store Image
        $this->storeImage($staff);

        //create staff access to mms
        $this->createAccessToSystem($mms_data,$staff);

        return redirect('staff');
        // return redirect('staff/'.$staff->id);
    }



    public function importFromExcel() //We will modify this function letter the process of uploading file can be done from interface
    {
        if (Gate::denies('access',['staff','store'])){
            abort(403, 'Access Denied');
        }

        ini_set('max_execution_time', '6000');

        $filename = 'staff.xlsx';
        $file_path = public_path().'/storage/imports/'.$filename;
        $importedExcel = (new ExcelImport)->toArray($file_path);
        $importedExcelRows = $importedExcel[0];

        //Import Staff
        $n = 0;
        foreach($importedExcelRows as $importedExcelRow){
            $n++; if($n == 1){ continue; } //Skip first row with titles

            $fullName = strtoupper(strtolower($importedExcelRow[0]));
            $gender = trim($importedExcelRow[1]);
            $officialEmail = trim($importedExcelRow[2]);
            $project = $importedExcelRow[3];
            $supervisorId = strtoupper(strtolower($importedExcelRow[4]));

            $existingStaff = Staff::where('official_email', '=', $officialEmail)->first();
            $existingUser = User::where('email', '=', $officialEmail)->first();

            if(!isset($existingStaff->id) && !isset($existingUser->id) && !in_array($officialEmail,[null,""])){
                $names = Helper::getNamesFromFullName($fullName);

                //dd($names);

                $staff = new Staff();
                $staff->first_name = $names['first_name'];
                $staff->middle_name = $names['middle_name'];
                $staff->last_name = $names['last_name'];
                $staff->gender = $gender;
                $staff->official_email = $officialEmail;
                $staff->duty_station = "Shinyanga";
                $staff->staff_status = "Active";
                $staff->department_id = "1";
                $staff->job_title_id = "19";
                $staff->save();

                if(isset($staff->id)){
                    //add staff as a system user
                    $user = User::create([
                        'name' => $staff->first_name.' '.$staff->last_name,
                        'email' => $officialEmail,
                        'category' => 'staff',
                        'role_id' => 6,
                        'password' => Hash::make('password'),
                        'status' => 'active',
                    ]);

                    //update staff
                    $staff->user_id = $user->id;
                    $staff->save();
                }

            }else{
                //Update Supervisor for existing staff
                if(!in_array($supervisorId, ["", null])){
                    $supervisor = Supervisor::find($supervisorId);
                    if($supervisor->id){
                        $existingStaff->supervisor_id = $supervisor->staff_id;
                        $existingStaff->save();
                    }
                }
            }

        }

        //Assign Supervisors


        return redirect('staff');
    }



    public function show(Staff $staff)
    {
        if (Gate::denies('access',['staff','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'staff';
        $controller_name = 'staff';
        $view_type = 'show';

        //check if this user have been added to user table (if can login to the system)
        $feedback = $this->checkIfCanAccessSystem($staff);
        $mms_access = ucwords($feedback['mms_access']);
        $role_id = $feedback['role_id'];

        $staff_status_change = StaffStatusChange::where('staff_id','=',$staff->id)
            ->where('status','=',$staff->staff_status)->latest()->first();

        //dd($staff_status_change);

        $role_name = '';
        if($role_id != ''){
            $role = SystemRole::find($role_id);
            $role_name = ucwords(str_replace('-', ' ', $role->role_name));
        }

        return view('staff.staff.show',
            compact( 'staff','mms_access', 'role_name','staff_status_change',
                'model_name', 'controller_name', 'view_type'));
    }


    public function edit(Staff $staff)
    {
        if (Gate::denies('access',['staff','edit'])){
            abort(403, 'Access Denied');
        }

        $departments = Department::all();
        $job_titles = StaffJobTitle::all();
        $regions = Region::all();
        $system_roles = SystemRole::get_system_roles_list();
        $supervisors = Staff::get_supervisors();

        $department_id = $staff->department_id;
        $job_title_id = $staff->job_title_id;
        $gender = $staff->gender;
        $staff_status = $staff->staff_status;
        $duty_station = $staff->duty_station;
        $supervisor_id = $staff->supervisor_id;


        $date_of_status_change = '';
        $status_change_description = '';
        $status_change_attachments = '';
        $staff_status_change = StaffStatusChange::where('staff_id','=',$staff->id)
            ->where('status','=',$staff->status)->latest()->first();
        if( isset($staff_status_change->id)){
            $date_of_status_change = $staff_status_change->date;
            $status_change_description = $staff_status_change->description;
            $status_change_attachments = $staff_status_change->attachments;
        }

        //check if this user have been added to user table (if can login to the system)
        $feedback = $this->checkIfCanAccessSystem($staff);
        $mms_access = $feedback['mms_access'];
        $role_id = $feedback['role_id'];

        $model_name = 'staff';
        $controller_name = 'staff';
        $view_type = 'create';

        return view('staff.staff.edit',
            compact( 'staff', 'system_roles', 'mms_access', 'role_id',
                'departments','job_titles', 'regions', 'supervisors', 'supervisor_id',
                'department_id', 'job_title_id', 'gender','staff_status', 'duty_station',
                'date_of_status_change','status_change_description','status_change_attachments',
                'model_name', 'controller_name', 'view_type'));

    }


    public function update(Request $request, Staff $staff)
    {
        if (Gate::denies('access',['staff','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();
        $staff_data = $data['staff_data'];
        $mms_data = $data['mms_data'];
        $staff_status_data = $data['staff_status'];

        $staff_data['date_of_termination'] = $staff_status_data['date_of_status_change'];
        $staff_original_status = $staff->staff_status;

        //update staff
        $staff->update($staff_data);

        //update staff status
        if( $staff_status_data['staff_status'] != 'Active' &&  $staff_status_data['staff_status'] != $staff_original_status ){

            $status_change = new StaffStatusChange();
            $status_change->staff_id = $staff->id;
            $status_change->status = $staff_status_data['staff_status'];
            $status_change->date = $staff_status_data['date_of_status_change'];
            $status_change->description = $staff_status_data['status_change_description'];
            $status_change->attachments = null;
            $status_change->save();

            //Update Status Attachments
            $this->storeStatusAttachments($status_change);
        }

        //Update Image
        $this->storeImage($staff);

        //update staff access to mms
        $this->updateAccessToSystem($mms_data,$staff);

        return redirect('staff/'.$staff->id);


    }


    public function supervisorsUpdate(Request $request)
    {
        if (Gate::denies('access',['staff','edit'])){
            abort(403, 'Access Denied');
        }


        $data = $this->validateSpvUpdateRequest();
        $staff_id = $data['staff_id'];
        $supervisor_id = $data['supervisor_id'];

        //update staff
        $staff = Staff::find($staff_id);
        $staff->supervisor_id = $supervisor_id;
        $staff->save();

        //update supervisors in all timesheets which are not submitted by this employee timesheet
        TimeSheet::change_timesheet_supervisor($staff_id, $supervisor_id);

        return redirect('staff_supervisors');

    }


    public function destroy(Staff $staff)
    {
        if (Gate::denies('access',['staff','delete'])){
            abort(403, 'Access Denied');
        }

        $staff->delete();

        return redirect('staff');
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


    private function storeStatusAttachments($status_change){

        if ( request()->has('status_change_attachments') ){
            if(count(request()->status_change_attachments)>0){

                $allowedFileExtension=['pdf','jpg','png','docx','doc','zip'];

                $attachment_names = [];
                $files = request()->file('status_change_attachments');
                foreach($files as $file){

                    // $filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $check = in_array($extension,$allowedFileExtension);
                    //dd($check);
                    if($check)
                    {
                        $attachment_names[] = $file->store('staff_status_attachments','public');
                    }

                }

                $status_change->update([
                    'attachments' => json_encode($attachment_names)
                ]);

            }
        }

    }


    public function createAccessToSystem($mms_data,$staff){ //we update update access to the system by modifying users table

        if($mms_data['mms_access'] == 'yes'){

            $role_id = $mms_data['role_id'];

            $email = '';
            //the prefered email in system is official-email, if it is not provided use personal email
            $staff->official_email = '' ? $email = $staff->personal_email : $email= $staff->official_email;

            //add member as a system user
            $user = User::create([
                'name' => $staff->first_name.' '.$staff->last_name,
                'email' => $email,
                'category' => 'staff',
                'role_id' => $role_id,
                'password' => Hash::make('password'),
                'status' => 'active',
            ]);

            //update staff
            $staff->user_id = $user->id;
            $staff->save();

        }

        elseif($mms_data['mms_access'] == 'no'){
            //do nothing
        }

    }


    public function updateAccessToSystem($mms_data,$staff){ //we update update access to the system by modifying users table


        if($mms_data['mms_access'] == 'yes'){

            $role_id = $mms_data['role_id'];
            $user_id = $staff->user_id;

            $email = '';
            //the preferred email in system is official-email, if it is not provided use personal email
            $staff->official_email == '' ? $email = $staff->personal_email : $email= $staff->official_email;

            if ( $user_id == ''){//means staff have got no user id

                //add member as a system user
                $user = User::create([
                    'name' => $staff->first_name.' '.$staff->last_name,
                    'email' => $email,
                    'category' => 'staff',
                    'role_id' => $role_id,
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ]);

                //update staff
                $staff->user_id = $user->id;
                $staff->save();
            }

            else{ //this means staff have user id

                $user_status = 'inactive';

                ///if Staff is not active then restrict his/her access to the system
                if($staff->staff_status == 'Active'){ $user_status = 'active';}

                //update staff info in users table
                User::where('id',$user_id)->update([
                    'name' => $staff->first_name.' '.$staff->last_name,
                    'email' => $email,
                    'category' => 'staff',
                    'role_id' => $role_id,
                    'status'=>$user_status,
                ]);
            }

        }
        elseif($mms_data['mms_access'] == 'no'){

            $user_id = $staff->user_id;

            if ( $user_id == ''){//means staff have got no user id

                //do nothing
            }

            else{ //this means staff have user id

                //suspend user from accessing the system
                User::where('id',$user_id)->update(['status'=>'inactive']);
            }

        }
    }


    public function checkIfCanAccessSystem($staff){

        $mms_access = '';
        $role_id = '';

        if ($staff->user_id == '' || $staff->user_id == null){//means staff have got no user id
            $mms_access = 'no';
            $role_id = '';
        }
        elseif(($staff->user_id != '' && $staff->user_id != null) && $staff->user->status == 'inactive'){
            $mms_access = 'no';
            $role_id = $staff->user->role_id;
        }
        else{ //this means staff have user id
            $mms_access = 'yes';
            $role_id = $staff->user->role_id;
        }

        return ['mms_access'=>$mms_access, 'role_id'=> $role_id];

    }


    private function validateRequest(){

        $staff_data =  request()->validate([
            'staff_no' =>  '',
            'first_name' =>  'required',
            'middle_name' =>  '',
            'last_name' =>  'required',
            'gender' =>  'required',
            'phone_no' =>  'nullable|numeric',
            'official_email' =>  'e-mail|required',
            'personal_email' =>  'e-mail|nullable',
            'duty_station' =>  'required',
            'place_of_birth' =>  '',
            'home_address' =>  '',
            'date_of_birth' =>  '',
            'date_of_employment' =>  'nullable',
            'date_of_termination' =>  'nullable',
            'staff_status' =>  'required',
            'image' =>  'sometimes|file|image|max:3000',
            'signature' =>  'sometimes|file|image|max:3000',
            'user_id' =>  '',
            'department_id' =>  'required',
            'job_title_id' =>  'required',
            'supervisor_id' =>  'nullable',
        ]);

        $staff_status =  request()->validate([
            'staff_status' =>  'required',
            'date_of_status_change' =>  'nullable',
            'status_change_description' =>  'nullable',
            'status_change_attachments' =>  '',
        ]);


        $mms_data = request()->validate([
            'mms_access' =>  'required',
            'role_id' =>  '',
        ]);

        return [
            'staff_data'=>$staff_data,
            'staff_status'=>$staff_status,
            'mms_data'=>$mms_data
        ];

    }


    private function validateSpvUpdateRequest(){

        return $data =  request()->validate([
            'staff_id' =>  'required',
            'supervisor_id' =>  'required',
        ]);

    }


    public function viewDocument($filename){
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "\\app\\public\\staff_status_attachments\\" . $filename;
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




}
