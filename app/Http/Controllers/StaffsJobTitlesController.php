<?php

namespace App\Http\Controllers;

use App\Models\StaffJobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StaffsJobTitlesController extends Controller
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
        if (Gate::denies('access',['staff_job_titles','view'])){
            abort(403, 'Access Denied');
        }

        $staff_job_titles = StaffJobTitle::all();

        $model_name = 'staff_job_title';
        $controller_name = 'staff_job_titles';
        $view_type = 'index';
//
        return view('staff.job_titles.index', compact('staff_job_titles','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['staff_job_titles','store'])){
            abort(403, 'Access Denied');
        }

        $staff_job_title = new StaffJobTitle();

        $model_name = 'staff_job_title';
        $controller_name = 'staff_job_titles';
        $view_type = 'create';

        return view('staff.job_titles.create',compact( 'staff_job_title','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['staff_job_titles','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $staffJobTitle = new StaffJobTitle();
        $staffJobTitle->title = $request->title;
        $staffJobTitle->save();

        return redirect('staff_job_titles/'.$staffJobTitle->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaffJobTitle  $staffJobTitle
     * @return \Illuminate\Http\Response
     */
    public function show(StaffJobTitle $staffJobTitle)
    {
        if (Gate::denies('access',['staff_job_titles','view'])){
            abort(403, 'Access Denied');
        }

        $staff_job_title = $staffJobTitle;
        $model_name = 'staff_job_title';
        $controller_name = 'staff_job_titles';
        $view_type = 'show';

        return view('staff.job_titles.show',compact( 'staff_job_title','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaffJobTitle  $staffJobTitle
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffJobTitle $staffJobTitle)
    {
        if (Gate::denies('access',['staff_job_titles','edit'])){
            abort(403, 'Access Denied');
        }

        $staff_job_title = $staffJobTitle;

        $model_name = 'staff_job_title';
        $controller_name = 'staff_job_titles';
        $view_type = 'edit';

        return view('staff.job_titles.edit',compact( 'staff_job_title','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffJobTitle  $staffJobTitle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffJobTitle $staffJobTitle)
    {
        if (Gate::denies('access',['staff_job_titles','edit'])){
            abort(403, 'Access Denied');
        }


        $staffJobTitle->update($this->validateRequest());

        return redirect('staff_job_titles/'.$staffJobTitle->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffJobTitle  $staffJobTitle
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffJobTitle $staffJobTitle)
    {
        if (Gate::denies('access',['staff_job_titles','delete'])){
            abort(403, 'Access Denied');
        }

        $staffJobTitle->delete();

        return redirect('staff_job_titles');
    }


    private function validateRequest(){

        return request()->validate([
            'title' => 'required',
        ]);

    }


}