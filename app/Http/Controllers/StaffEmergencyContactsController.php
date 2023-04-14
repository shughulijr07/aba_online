<?php

namespace App\Http\Controllers;

use App\Models\StaffEmergencyContact;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class StaffEmergencyContactsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['staff_emergency_contacts','view'])){
            abort(403, 'Access Denied');
        }


        $staff = auth()->user()->staff;
        $staff_emergency_contacts = $staff->emergency_contacts;

        //dd($staff_emergency_contacts_old);

        $model_name = 'staff_emergency_contact';
        $controller_name = 'staff_emergency_contacts';
        $view_type = 'index';



        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Emergency Contacts',
            'item_id'=> '',
            'description'=> 'Viewed '.$staff_name.'\'s Emergency Contacts List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.emergency_contacts.index',
            compact('staff','staff_emergency_contacts','model_name', 'controller_name', 'view_type'));
    }



    public function create()
    {
        if (Gate::denies('access',['staff_emergency_contacts','store'])){
            abort(403, 'Access Denied');
        }

        $staff = auth()->user()->staff;
        $staff_emergency_contact = new StaffEmergencyContact();

        $gender = '';

        $model_name = 'staff_emergency_contact';
        $controller_name = 'staff_emergency_contacts';
        $view_type = 'create';



        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Emergency Contacts',
            'item_id'=> '',
            'description'=> 'Viewed '.$staff_name.'\'s Emergency Contacts Recording Page',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return view('staff.emergency_contacts.create',
            compact( 'staff','staff_emergency_contact', 'gender',
                'model_name', 'controller_name', 'view_type'));
    }



    public function store(Request $request)
    {
        if (Gate::denies('access',['staff_emergency_contacts','store'])){
            abort(403, 'Access Denied');
        }


        $data = $this->validateRequest();
        $data['staff_id'] = auth()->user()->staff->id;
        //dd($data);

        //store staff
        $staff_emergency_contact = StaffEmergencyContact::create($data);

        //store Image
        $this->storeImage($staff_emergency_contact);



        //record user activity
        $staff = $staff_emergency_contact->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Recording',
            'item'=> 'Emergency Contacts',
            'item_id'=> $staff_emergency_contact->id,
            'description'=> 'Added One Emergency Contact To '.$staff_name.'\'s Emergency Contacts List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        //return redirect('staff_emergency_contacts');
        return redirect('staff_emergency_contacts/'.$staff_emergency_contact->id);

    }



    public function show(StaffEmergencyContact $staffEmergencyContact)
    {
        if (Gate::denies('access',['staff','view'])){
            abort(403, 'Access Denied');
        }

        $staff_emergency_contact = $staffEmergencyContact;

        $model_name = 'staff_emergency_contact';
        $controller_name = 'staff_emergency_contacts';
        $view_type = 'show';



        //record user activity
        $staff = $staff_emergency_contact->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Emergency Contact',
            'item_id'=> $staff_emergency_contact->id,
            'description'=> 'Viewed Staff Emergency Contact Information',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return view('staff.emergency_contacts.show',
            compact( 'staff_emergency_contact',
                'model_name', 'controller_name', 'view_type'));
    }




    public function edit(StaffEmergencyContact $staffEmergencyContact)
    {
        if (Gate::denies('access',['staff_emergency_contacts','edit'])){
            abort(403, 'Access Denied');
        }



        $staff_emergency_contact  = $staffEmergencyContact;
        $staff = $staff_emergency_contact->staff;

        $gender = $staff_emergency_contact->gender;

        $model_name = 'staff_emergency_contact';
        $controller_name = 'staff_emergency_contacts';
        $view_type = 'edit';


        //record user activity
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'View',
            'item'=> 'Staff Emergency Contact',
            'item_id'=> $staff_emergency_contact->id,
            'description'=> 'Viewed '.$staff_name.'\'s Emergency Contact In Editing Mode',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);


        return view('staff.emergency_contacts.edit',
            compact( 'staff','staff_emergency_contact', 'gender',
                'model_name', 'controller_name', 'view_type'));

    }



    public function update(Request $request, StaffEmergencyContact $staffEmergencyContact)
    {
        if (Gate::denies('access',['staff_emergency_contacts','edit'])){
            abort(403, 'Access Denied');
        }



        $data = $this->validateRequest();
        $data['staff_id'] = auth()->user()->staff->id;
        //dd($data);

        //store staff
        $staff_emergency_contact = $staffEmergencyContact;
        $staff_emergency_contact->update($data);

        //store Image
        $this->storeImage($staff_emergency_contact);



        //record user activity
        $staff = $staff_emergency_contact->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Updating',
            'item'=> 'Staff Emergency Contact',
            'item_id'=> $staff_emergency_contact->id,
            'description'=> 'Updated '.$staff_name.'\'s Emergency Contact Information',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        return redirect('staff_emergency_contacts/'.$staff_emergency_contact->id);


    }



    public function destroy(StaffEmergencyContact $staffEmergencyContact)
    {
        if (Gate::denies('access',['staff_emergency_contacts','delete'])){
            abort(403, 'Access Denied');
        }


        //record user activity
        $staff = $staffEmergencyContact->staff;
        $staff_name = ucwords($staff->first_name.' '.$staff->last_name);
        $activity = [
            'action'=> 'Deleting',
            'item'=> 'Staff Emergency Contact',
            'item_id'=> $staffEmergencyContact->id,
            'description'=> 'Deleted Emergency Contact with ID'.$staffEmergencyContact->id.' From '.$staff_name.'\'s Emergency Contacts List',
            'user_id'=> auth()->user()->id,
        ];

        $activity_category = 'major';
        UserActivity::record_user_activity($activity, $activity_category);



        $staffEmergencyContact->delete();

        return redirect('staff_emergency_contacts');
    }









    private function storeImage($staff_emergency_contact){

        if ( request()->has('image') ){
            $staff_emergency_contact->update([
                'image' => request()->image->store('staff_emergency_contacts_images','public'),
            ]);
        }

        if ( request()->has('certificate') ){
            $staff_emergency_contact->update([
                'certificate' => request()->certificate->store('staff_emergency_contacts_certificates','public'),
            ]);
        }

    }



    private function validateRequest(){

        return $data =  request()->validate([
            'full_name' =>  'required',
            'gender' =>  'required',
            'relationship' =>  'required',
            'physical_address' =>  'required',
            'email' =>  'nullable',
            'city' =>  'required',
            'cell_phone' =>  'required',
            'home_phone' =>  'nullable',
            'business_phone' =>  'nullable',
            'image' =>  'sometimes|file|image|max:3072',
        ]);


    }


}
