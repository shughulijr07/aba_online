<?php

namespace App\Http\Controllers;

use App\Models\ActiveProject;
use App\Models\Project;
use App\Models\TimeSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ActiveProjectsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['active_projects','view'])){
            abort(403, 'Access Denied');
        }

        $active_projects = ActiveProject::where('year','=',date('Y'))->get();
        $months = TimeSheet::$months;

        //dd($active_projects);

        $model_name = 'active_project';
        $controller_name = 'active_projects';
        $view_type = 'index';

        return view('projects.active_projects.index',
            compact('active_projects', 'months', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['active_projects','store'])){
            abort(403, 'Access Denied');
        }

        $all_projects = Project::all();
        $active_project = new ActiveProject();
        $months = TimeSheet::$months;
        $active_projects = [];

        $model_name = 'active_project';
        $controller_name = 'active_projects';
        $view_type = 'create';

        return view('projects.active_projects.create',
            compact( 'all_projects', 'active_project','active_projects', 'months',
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
        if (Gate::denies('access',['active_projects','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();
        $year = $data['year'];
        $month = $data['month'];
        $projects = $data['projects']; //dd(json_encode($projects));

        //check if Active Projects For This Month Have Already Been Added
        $active_project = ActiveProject::where('year','=',$year)->where('month','=',$month)->get();

        if( count($active_project) > 0 ){
            return redirect('active_projects/create')->with('message','Active Projects For This Month Have Already Been Set Previously');
        }
        else{
            $active_project = new ActiveProject();
            $active_project->year = $year;
            $active_project->month = $month;
            $active_project->projects = json_encode($projects);
            $active_project->save();

            return redirect('active_projects/'.$active_project->id);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ActiveProject  $activeProject
     * @return \Illuminate\Http\Response
     */
    public function show(ActiveProject $activeProject)
    {
        if (Gate::denies('access',['active_projects','view'])){
            abort(403, 'Access Denied');
        }

        $active_project = $activeProject;
        $all_projects = Project::all();
        $months = TimeSheet::$months;
        $active_projects = json_decode($active_project->projects);

        //dd($active_projects);

        $model_name = 'active_project';
        $controller_name = 'active_projects';
        $view_type = 'show';

        return view('projects.active_projects.show',
            compact( 'active_project','active_projects', 'all_projects', 'months',
                'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ActiveProject  $activeProject
     * @return \Illuminate\Http\Response
     */
    public function edit(ActiveProject $activeProject)
    {
        if (Gate::denies('access',['active_projects','edit'])){
            abort(403, 'Access Denied');
        }

        $active_project = $activeProject;
        $all_projects = Project::all();
        $months = TimeSheet::$months;
        $active_projects = json_decode($active_project->projects);

        $model_name = 'active_project';
        $controller_name = 'active_projects';
        $view_type = 'edit';

        return view('projects.active_projects.edit',
            compact( 'active_project','active_projects', 'all_projects', 'months',
                'model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ActiveProject  $activeProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActiveProject $activeProject)
    {
        if (Gate::denies('access',['active_projects','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();
        $year = $data['year'];
        $month = $data['month'];
        $projects = $data['projects'];

        $activeProject->year = $year;
        $activeProject->month = $month;
        $activeProject->projects = json_encode($projects);
        $activeProject->save();

        return redirect('active_projects/'.$activeProject->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ActiveProject  $activeProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActiveProject $activeProject)
    {
        if (Gate::denies('access',['active_projects','delete'])){
            abort(403, 'Access Denied');
        }

        $activeProject->delete();

        return redirect('active_projects');
    }


    private function validateRequest(){

        return request()->validate([
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|digits_between: 1,12',
            'projects' => 'required',
        ]);

    }

}
