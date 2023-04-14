<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeaveTypesController extends Controller
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
        if (Gate::denies('access',['leave_types','view'])){
            abort(403, 'Access Denied');
        }

        $leave_types = LeaveType::all();

        $model_name = 'leave_type';
        $controller_name = 'leave_types';
        $view_type = 'index';

        return view('leave_types.index',
            compact('leave_types','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['leave_types','store'])){
            abort(403, 'Access Denied');
        }

        $leave_type = new LeaveType();

        $model_name = 'leave_type';
        $controller_name = 'leave_types';
        $view_type = 'create';

        return view('leave_types.create',
            compact( 'leave_type','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['leave_types','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $leave_type = new LeaveType();
        $leave_type->name = $request->name;
        $leave_type->key = $request->key;
        $leave_type->status = $request->status;
        $leave_type->days = $request->days;
        $leave_type->period = $request->period;
        $leave_type->save();

        return redirect('leave_types/'.$leave_type->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leaveType)
    {
        if (Gate::denies('access',['leave_types','view'])){
            abort(403, 'Access Denied');
        }

        $leave_type = $leaveType;

        $model_name = 'leave_type';
        $controller_name = 'leave_types';
        $view_type = 'show';

        return view('leave_types.show',
            compact( 'leave_type','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leaveType)
    {
        if (Gate::denies('access',['leave_types','edit'])){
            abort(403, 'Access Denied');
        }

        $leave_type = $leaveType;

        $model_name = 'leave_type';
        $controller_name = 'leave_types';
        $view_type = 'edit';

        return view('leave_types.edit',
            compact( 'leave_type','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        if (Gate::denies('access',['leave_types','edit'])){
            abort(403, 'Access Denied');
        }

        $leaveType->update($this->validateRequest());

        return redirect('leave_types/'.$leaveType->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaveType  $leaveType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leaveType)
    {
        if (Gate::denies('access',['leave_types','delete'])){
            abort(403, 'Access Denied');
        }

        $leaveType->delete();

        return redirect('leave_types');
    }


    private function validateRequest(){

        return request()->validate([
            'name' => 'required',
            'key' => 'required',
            'status' => 'required',
            'days' => 'required|numeric',
            'period' => 'required|numeric',
        ]);

    }


}
