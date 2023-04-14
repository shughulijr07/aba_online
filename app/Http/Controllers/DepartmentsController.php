<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentsController extends Controller
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
        if (Gate::denies('access',['departments','view'])){
            abort(403, 'Access Denied');
        }

        $departments = Department::all();

        $model_name = 'department';
        $controller_name = 'departments';
        $view_type = 'index';

        return view('staff.departments.index', compact('departments','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['departments','store'])){
            abort(403, 'Access Denied');
        }

        $department = new Department();

        $model_name = 'department';
        $controller_name = 'departments';
        $view_type = 'create';

        return view('staff.departments.create',compact( 'department','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['departments','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        return redirect('departments/'.$department->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        if (Gate::denies('access',['departments','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'department';
        $controller_name = 'departments';
        $view_type = 'show';

        return view('staff.departments.show',compact( 'department','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        if (Gate::denies('access',['departments','edit'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'department';
        $controller_name = 'departments';
        $view_type = 'edit';

        return view('staff.departments.edit',compact( 'department','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        if (Gate::denies('access',['departments','edit'])){
            abort(403, 'Access Denied');
        }

        $department->update($this->validateRequest());

        return redirect('departments/'.$department->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if (Gate::denies('access',['departments','delete'])){
            abort(403, 'Access Denied');
        }

        $department->delete();

        return redirect('departments');
    }


    private function validateRequest(){

        return request()->validate([
            'name' => 'required',
        ]);

    }

}

