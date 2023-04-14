<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupervisorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        if (Gate::denies('access',['supervisors','view'])){
            abort(403, 'Access Denied');
        }


        $supervisors = Supervisor::all();


        $model_name = 'supervisor';
        $controller_name = 'supervisors';
        $view_type = 'index';


        return view('supervisors.index',
            compact('supervisors',
                'model_name', 'controller_name', 'view_type'));
    }


    public function create()
    {
        if (Gate::denies('access',['supervisors','store'])){
            abort(403, 'Access Denied');
        }

        $all_staff = Staff::get_valid_staff_list();
        $supervisors = Supervisor::all();



        $model_name = 'supervisor';
        $controller_name = 'supervisors';
        $view_type = 'create';

        return view('supervisors.create',
            compact( 'all_staff', 'supervisors',
                'model_name', 'controller_name', 'view_type'));
    }


    public function store(Request $request)
    {
        $data = $this->validateLeaveRequest();

        //check if supervisor exists in table
        $supervisor = Supervisor::where('staff_id','=',$data['staff_id'])->first();

        if( isset($supervisor->id)){
            return redirect()->back()->with('message','The Supervisor you want to add have already been added previously');

        }else{

            $supervisor = new Supervisor();
            $supervisor->staff_id = $data['staff_id'];
            $supervisor->save();

            return redirect()->back()->with('message','Supervisor was added successfully');

        }

    }



    public function destroy(Supervisor $supervisor)
    {
        if (Gate::denies('access',['supervisors','delete'])){
            abort(403, 'Access Denied');
        }

        $supervisor->delete();

        return redirect()->back()->with('message','Supervisor was removed successfully');
    }



    public function delete($supervisor_id)
    {
        if (Gate::denies('access',['supervisors','delete'])){
            abort(403, 'Access Denied');
        }

        $supervisor = Supervisor::find($supervisor_id);
        $supervisor->delete();

        return redirect()->back()->with('message','Supervisor was removed successfully');
    }



    public function validateLeaveRequest(){

        return  request()->validate([
            'staff_id' =>  'required',
        ]);


    }
}
