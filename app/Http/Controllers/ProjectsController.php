<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CompanyInformation;
use App\Models\Project;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['projects','view'])){
            abort(403, 'Access Denied');
        }

        $companyLogoBase64 = CompanyInformation::$companyLogoBase64;
        $companyLogoPath = url(CompanyInformation::$companyLogoLocation);

        $model_name = 'project';
        $controller_name = 'projects';
        $reportTitle = 'PROJECTS';
        $view_type = 'index';

        $all_projects = Project::all();
        return view('projects.projects.index',
            compact('companyLogoBase64','companyLogoPath',
                'reportTitle', 'model_name', 'controller_name', 'view_type', 'all_projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['projects','store'])){
            abort(403, 'Access Denied');
        }

        $project = new Project();

        $model_name = 'project';
        $controller_name = 'projects';
        $view_type = 'create';

        return view('projects.projects.create',
            compact( 'project','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['projects','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $project = new Project();
        $project->number = $request->number;
        $project->name = $request->name;
        $project->save();

        return redirect('projects/'.$project->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        if (Gate::denies('access',['projects','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'project';
        $controller_name = 'projects';
        $view_type = 'show';

        return view('projects.projects.show',
            compact( 'project','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        if (Gate::denies('access',['projects','edit'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'project';
        $controller_name = 'projects';
        $view_type = 'edit';

        return view('projects.projects.edit',
            compact( 'project','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        if (Gate::denies('access',['projects','edit'])){
            abort(403, 'Access Denied');
        }

        $project->update($this->validateRequest());

        return redirect('projects/'.$project->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (Gate::denies('access',['projects','delete'])){
            abort(403, 'Access Denied');
        }

        $project->delete();

        return redirect('projects');
    }


    private function validateRequest(){

        return request()->validate([
            'number' => 'required',
            'name' => 'required',
        ]);

    }


    public function ajaxGetList(Request $request){

        if(!request()->ajax()) exit;

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];//Column index
        $columnName = $columnName_arr[$columnIndex]['data'];//Column name
        $columnSortOrder = $order_arr[0]['dir'];//asc or desc
        $searchValue = $search_arr['value'];//Search value

        $searchTable = "projects";

        //Convert Column Names
        if($columnName == "#"){$columnName = "id";}
        if($columnName == "Project Name"){$columnName = "name"; $searchTable = "projects";}
        if($columnName == "Project Code"){$columnName = "number"; $searchTable = "projects";}
        if($columnName == "Action"){$columnName = "id";}



        $exactMatchColumns = [ 'id'];

        $records = [];
        $totalRecords = Project::select('count(*) as allcount');
        $totalRecordswithFilter = 0;


        $tableQuery = DB::table('projects');

        $fieldQuery = $tableQuery->select(
            'projects.id as id',
            'projects.name as project_name',
            'projects.number as project_code'
        );


        if(empty($request->filters))
        {
            $totalRecordswithFilter = $tableQuery->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
            //$totalRecordswithFilter = $tableQuery->select('count(id) as allcount')->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
            $records = $fieldQuery;
            $records = $records->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
            $records = $records->orderBy($searchTable.'.'.$columnName,$columnSortOrder);

            $data_arr = array();
        }else{

            //filters
            foreach ($request->filters as $filter_name => $filter_value ){
                $searchValue = $filter_value;
                $columnName = $filter_name;
                if(!in_array($searchValue, ['',null])){
                    if(in_array($columnName, $exactMatchColumns)){
                        $fieldQuery = $fieldQuery->where($searchTable.'.'.$columnName, '=', $searchValue);
                    }else{
                        $fieldQuery = $fieldQuery->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
                    }
                }
            }

            // Total records
            $totalRecordswithFilter = $tableQuery->select('count(*) as allcount');
            foreach ($request->filters as $filter_name => $filter_value ){
                $searchValue = $filter_value;
                $columnName = $filter_name;
                if(!in_array($searchValue, ['',null])){
                    if(in_array($columnName, $exactMatchColumns)){
                        $totalRecordswithFilter = $totalRecordswithFilter->where($searchTable.'.'.$columnName, '=', $searchValue);
                    }else{
                        $totalRecordswithFilter = $totalRecordswithFilter->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
                    }
                }
            }

            // Fetch records
            $records = $fieldQuery->orderBy($searchTable.'.'.$columnName,$columnSortOrder);
            foreach ($request->filters as $filter_name => $filter_value ){
                $searchValue = $filter_value;
                $columnName = $filter_name;
                if(!in_array($searchValue, ['',null])){
                    if(in_array($columnName, $exactMatchColumns)){
                        $records = $records->where($searchTable.'.'.$columnName, '=', $searchValue)->select('*');
                    }else{
                        $records = $records->where($searchTable.'.'.$columnName, 'like', '%' .$searchValue . '%');
                    }
                }
            }

            $data_arr = array();
        }



        $totalRecordswithFilter = $totalRecordswithFilter->count();
        $records = $records->skip($start)->take($rowperpage)->get();
        $totalRecords = $totalRecords->count();

        $no = $start +1;
        foreach($records as $record){

            //$actions = '<button  type="button"class="btn btn-success btn-sm m-1 viewRecord" data-id="'.$record->id.'">View</button>';
            $actions ='<a href="'.url('/projects/'.$record->id).'" type="button" class="btn btn-success btn-sm m-1"><i class="dropdown-icon lnr-eye"> </i><span>View</span></a>';


            if (!Gate::denies('access',['projects','edit'])){
                //$actions .= '<button  type="button" class="btn btn-warning btn-sm m-1 editRecord" data-id="'.$record->id.'">Edit</button>';
                $actions .='<a href="'.url('/projects/'.$record->id.'/edit').'"  type="button" class="btn btn-warning btn-sm m-1"><i class="dropdown-icon lnr-pencil"> </i><span>Edit</span></a>';
            }

            if (!Gate::denies('access',['projects','delete'])){
                $deleteFunction = "deleteRecord(" . "'".$record->id."'". "," ."'".$record->project_code."'". ")";
                $actions .= '<button  type="button" class="btn btn-danger btn-sm m-1"  onclick="'.$deleteFunction.'" data-id="'.$record->id.'" data-code="'.$record->project_code.'"><i class="dropdown-icon lnr-trash"> </i><span>Delete</span></button>';
            }

            $data_arr[] = array(
                "#" => $no,
                "Project Code" => $record->project_code,
                "Project Name" => $record->project_name,
                "Action" => $actions
            );


            $no++;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }


    public function ajaxDelete(Request $request)
    {

        if(!$request->ajax())
        {
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Oppsss! Bad Request'
            ]);
        }

        if (!Auth::check()){
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Please login first to perform this activity!'
            ]);
        }

        if (Gate::denies('access',['projects','store'])){
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Not Authorized!'
            ]);
        }


        $project = Project::find($request->id);

        if(isset($project->id)){

            $activities = $project->activities;
            foreach($activities as $activity){

                $activityId = $activity->id;
                $activity->delete();

                //record user activity
                $activity = [
                    'action'=> 'Delete',
                    'item'=> 'activities',
                    'item_id'=> $activityId,
                    'description'=> 'Deleted Activity With ID - '.$activityId,
                    'user_id'=> auth()->user()->id,
                ];

                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

            }

            $projectId = $project->id;
            $project->delete();

            //record user activity
            $activity = [
                'action'=> 'Delete',
                'item'=> 'projects',
                'item_id'=> $projectId,
                'description'=> 'Deleted New Project With ID - '.$projectId,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);


            return response()->json([
                'feedback' => 'success',
                'message' => 'Project Deleted Successfully'
            ]);


        }else{
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Oppsss! Unable to find specified Project'
            ]);
        }

    }

}
