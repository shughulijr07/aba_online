<?php

namespace App\Http\Controllers;

use App\Models\MyFunctions;
use App\Models\StaffDependent;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StaffDependentsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['staff_dependents','view'])){
            abort(403, 'Access Denied');
        }


        $staff = auth()->user()->staff;
        $dependents = $staff->dependents;

        //dd($dependents_old);

        $model_name = 'staff_dependent';
        $controller_name = 'staff_dependents';
        $view_type = 'index';



        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Dependents',
            'item_id'=> '',
            'description'=> 'Viewed '.$staff_name.'\'s Dependents List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.dependents.index',
            compact('staff','dependents','model_name', 'controller_name', 'view_type'));
    }



    public function create()
    {
        if (Gate::denies('access',['staff_dependents','store'])){
            abort(403, 'Access Denied');
        }

        $staff = auth()->user()->staff;
        $staff_dependent = new StaffDependent();
        $relationships = StaffDependent::$relationships;

        $gender = '';

        $model_name = 'staff_dependent';
        $controller_name = 'staff_dependents';
        $view_type = 'create';



        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Dependents',
            'item_id'=> '',
            'description'=> 'Viewed '.$staff_name.'\'s Dependents Recording Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        return view('staff.dependents.create',
            compact( 'staff','staff_dependent', 'gender','relationships',
                'model_name', 'controller_name', 'view_type'));
    }



    public function store(Request $request)
    {
        if (Gate::denies('access',['staff_dependents','store'])){
            abort(403, 'Access Denied');
        }


        $data = $this->validateRequest();
        $data['date_of_birth'] = MyFunctions::convert_date_to_mysql_format($data['date_of_birth']);
        $data['staff_id'] = auth()->user()->staff->id;
        //dd($data);

        //store staff
        $staff_dependent = StaffDependent::create($data);

        //store Image
        $this->storeImage($staff_dependent);



        //record user activity
        $staff = auth()->user()->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Adding',
            'item'=> 'Staff Dependents',
            'item_id'=> $staff_dependent->id,
            'description'=> 'Added Dependents To '.$staff_name.'\'s Dependents List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);

        //return redirect('staff_dependents');
        return redirect('staff_dependents/'.$staff_dependent->id);

    }



    public function show(StaffDependent $staffDependent)
    {
        if (Gate::denies('access',['staff','view'])){
            abort(403, 'Access Denied');
        }

        $staff_dependent = $staffDependent;

        $model_name = 'staff_dependent';
        $controller_name = 'staff_dependents';
        $view_type = 'show';



        //record user activity
        $staff = $staff_dependent->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Dependent',
            'item_id'=> $staff_dependent->id,
            'description'=> 'Viewed Staff Dependent Information',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return view('staff.dependents.show',
            compact( 'staff_dependent',
                'model_name', 'controller_name', 'view_type'));
    }


    public function edit(StaffDependent $staffDependent)
    {
        if (Gate::denies('access',['staff_dependents','edit'])){
            abort(403, 'Access Denied');
        }



        $staff_dependent  = $staffDependent;
        $staff = $staff_dependent->staff;
        $relationships = StaffDependent::$relationships;

        $gender = $staff_dependent->gender;

        $model_name = 'staff_dependent';
        $controller_name = 'staff_dependents';
        $view_type = 'edit';


        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Dependents',
            'item_id'=> $staff_dependent->id,
            'description'=> 'Viewed '.$staff_name.'\'s Dependent In Editing Mode',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.dependents.edit',
            compact( 'staff','staff_dependent', 'gender','relationships',
                'model_name', 'controller_name', 'view_type'));

    }



    public function update(Request $request, StaffDependent $staffDependent)
    {
        if (Gate::denies('access',['staff_dependents','edit'])){
            abort(403, 'Access Denied');
        }



        $data = $this->validateRequest();
        $data['date_of_birth'] = MyFunctions::convert_date_to_mysql_format($data['date_of_birth']);
        $data['staff_id'] = auth()->user()->staff->id;
        //dd($data);

        //store staff
        $staff_dependent = $staffDependent;
        $staff_dependent->update($data);

        //store Image
        $this->storeImage($staff_dependent);



        //record user activity
        $staff = $staff_dependent->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Updating',
            'item'=> 'Staff Dependents',
            'item_id'=> $staff_dependent->id,
            'description'=> 'Updated '.$staff_name.'\'s Dependent Information',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return redirect('staff_dependents/'.$staff_dependent->id);


    }



    public function destroy(StaffDependent $staffDependent)
    {
        if (Gate::denies('access',['staff_dependents','delete'])){
            abort(403, 'Access Denied');
        }


        //record user activity
        $staff = $staffDependent->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Deleting',
            'item'=> 'Staff Dependent',
            'item_id'=> $staffDependent->id,
            'description'=> 'Deleted Dependent with ID'.$staffDependent->id.' From '.$staff_name.'\'s Dependents List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        $staffDependent->delete();

        return redirect('staff_dependents');
    }






    private function storeImage($staff_dependent){

        if ( request()->has('image') ){
            $staff_dependent->update([
                'image' => request()->image->store('staff_dependents_images','public'),
            ]);
        }

        if ( request()->has('certificate') ){
            $staff_dependent->update([
                'certificate' => request()->certificate->store('staff_dependents_certificates','public'),
            ]);
        }

    }



    private function validateRequest(){

        return $data =  request()->validate([
            'full_name' =>  'required',
            'date_of_birth' =>  'required',
            'gender' =>  'required',
            'relationship' =>  'required',
            'birth_certificate_no' =>  'nullable',
            'to_be_on_medical' =>  'required',
            'image' =>  'sometimes|file|image|max:3072',
            'certificate' =>  'sometimes|mimes:zip,doc,docx,pdf,jpeg,jpg,png|max:3072',
        ]);


    }


    public function viewDocument($filename){
        // Check if file exists in app/storage/file folder
        $file_path = storage_path() . "\\app\\public\\staff_dependents_certificates\\" . $filename;
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
