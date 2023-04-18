<?php

namespace App\Http\Controllers;



use App\Models\Activity;
use App\Models\CompanyInformation;
use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImport;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeSheet;
use App\Models\TimesheetClient;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ActivitiesController extends Controller
{


    public function  getProject($number)
    {
        $project = Project::where('number', $number)->first();
        return $project;
    }

    public  function  getProjectActivitiesApi(Request $request)
    {
        // $number = $request->project_id;
        // $project = $this->getProject($number);
        // if( !$project ){
        //     return  response()->json(['status' => 'error', 'message' => 'something is wrong']);
        // }
        //  $activities = Activity::select('id', 'name', 'code')
        //         ->where('project_id', '=', $project->id)
        //         ->get();
        // return response()->json($activities);
        
        $project_number = $request->project_id;
        
        $client = $this->getProject($project_number);
        $client_id = $client->id;
        $timesheet_id = $request->timesheet_id;
        
        $timesheet_client = TimesheetClient::select('id')
        ->where('project_id', $client_id)
        ->where('time_sheet_id', $timesheet_id)
        ->first();

        $timesheet_client_id = $timesheet_client->id;

         if( !$timesheet_client ){
             return  response()->json(['status' => 'error', 'message' => 'something is wrong']);
         }
        $activities = Task::where('timesheet_client_id', $timesheet_client_id)
        ->get();
       
        return response()->json($activities);

    }

    public function index()
    {
        if (Gate::denies('access',['activities','view'])){
            abort(403, 'Access Denied');
        }


        $projects = Project::all();
        $companyLogoBase64 = CompanyInformation::$companyLogoBase64;
        $companyLogoPath = url(CompanyInformation::$companyLogoLocation);


        /************************************** TEST END **********************/
        $model_name = 'activity';
        $controller_name = 'activities';
        $reportTitle = 'ACTIVITIES';
        $view_type = 'index';

        // Added by UAT to take data from db and damp all activities to the index page
        $activities = Activity::all();

        return view('projects.activities.index',
            compact('projects', 'companyLogoBase64','companyLogoPath',
                'reportTitle', 'model_name', 'controller_name', 'view_type', 'activities'));
    }


    public function create()
    {
        if (Gate::denies('access',['activities','store'])){
            abort(403, 'Access Denied');
        }

        $activity = new Activity();
        $projects = Project::all();

        $model_name = 'activity';
        $controller_name = 'activities';
        $view_type = 'create';

        return view('projects.activities.create',
            compact( 'activity', 'projects',
                'model_name', 'controller_name','view_type'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('access',['activities','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();

        $activity = Activity::create($data);

        return redirect('activities/'.$activity->id);
    }



    public function importFromExcel() //We will modify this function letter the process of uploading file can be done from interface
    {
        if (Gate::denies('access',['activities','store'])){
            abort(403, 'Access Denied');
        }

        ini_set('max_execution_time', '60000');

        $filename = 'codes.xlsx';
        $file_path = public_path().'/storage/imports/'.$filename;
        $importedExcel = (new ExcelImport)->toArray($file_path);
        $importedExcelRows = $importedExcel[1];

        $n = 0;
        foreach($importedExcelRows as $importedExcelRow){
            $n++; if($n == 1){ continue; } //Skip first row with titles

            $activityCode = strtoupper(strtolower($importedExcelRow[0]));
            $activityName = $importedExcelRow[1];
            $projectCode = strtoupper(strtolower($importedExcelRow[2]));
            $projectName = $importedExcelRow[3];

            $project = new Project();
            $existingProject = Project::where('number', '=', $projectCode)->first();

            if(isset($existingProject->id)){
                $project = $existingProject;
            }else{
                $project->name = $projectName;
                $project->number = $projectCode;
                $project->save();
            }

            if(isset($project->id)){
                $existingActivity = Activity::where('code', '=', $activityCode)->first();

                if(!isset($existingActivity->id)){
                    $activity = new Activity();
                    $activity->name = $activityName;
                    $activity->code = $activityCode;
                    $activity->project_id = $project->id;
                    $activity->save();
                }


            }

        }


        return redirect('activities');
    }


    public function show(Activity $activity)
    {
        if (Gate::denies('access',['activities','view'])){
            abort(403, 'Access Denied');
        }

        $adjacent_item_ids = Helper::get_previous_and_next_item_ids_in_a_table('activities', $activity->id);
        $previous_field_id = $adjacent_item_ids['previous_id'];
        $next_field_id = $adjacent_item_ids['next_id'];


        $model_name = 'activity';
        $controller_name = 'activities';
        $view_type = 'show';

        return view('projects.activities.show',
            compact('activity',  'previous_field_id', 'next_field_id',
                'model_name', 'controller_name','view_type'));

    }


    public function edit(Activity $activity)
    {
        if (Gate::denies('access',['activities','edit'])){
            abort(403, 'Access Denied');
        }


        $projects = Project::all();

        $model_name = 'activity';
        $controller_name = 'activities';
        $view_type = 'edit';

        return view('projects.activities.edit',
            compact('activity', 'projects',
                'model_name', 'controller_name','view_type'));

    }


    public function update(Request $request, Activity $activity)
    {
        if (Gate::denies('access',['activities','edit'])){
            abort(403, 'Access Denied');
        }

        $activity->update($this->validateRequest());

        return redirect('activities/'.$activity->id);

    }


    public function destroy(Activity $activity)
    {
        if (Gate::denies('access',['activities','delete'])){
            abort(403, 'Access Denied');
        }

        $activity->delete();

        return redirect('activities');
    }


    private function validateRequest(){

        return request()->validate([
            'project_id' => 'required',
            'code' => 'required',
            'name' => 'required',
        ]);

    }

    public function ajaxGetActivitiesByProject(Request $request)
    {
        $activities ='';
        $project_id = $request->project_id;
        if($request->ajax())
        {
            $activities = DB::table('activities')
                ->select('id', 'name', 'code')
                ->where('project_id', '=', $project_id)
                ->get();
        }

        echo json_encode($activities);
    }


    public function getList(Request $request){

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

        $searchTable = "activities";


        //Convert Column Names
        if($columnName == "#"){$columnName = "id";}
        if($columnName == "Activity"){$columnName = "name"; $searchTable = "activities";}
        if($columnName == "Activity Code"){$columnName = "code"; $searchTable = "activities";}
        if($columnName == "Project"){$columnName = "name"; $searchTable = "projects";}
        if($columnName == "Project Code"){$columnName = "number"; $searchTable = "projects";}
        if($columnName == "Action"){$columnName = "id";}



        $exactMatchColumns = [ 'id','project_id'];

        $records = [];
        $totalRecords = Activity::select('count(*) as allcount');
        $totalRecordswithFilter = 0;


        $tableQuery = DB::table('activities')
            ->join('projects','projects.id','activities.project_id');

        $fieldQuery = $tableQuery->select(
            'activities.id as id',
            'activities.name as activity_name',
            'activities.code as activity_code',
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
            $actions ='<a href="'.url('/activities/'.$record->id).'" type="button" class="btn btn-success btn-sm m-1"><i class="dropdown-icon lnr-eye"> </i><span>View</span></a>';


            if (!Gate::denies('access',['activities','edit'])){
                //$actions .= '<button  type="button" class="btn btn-warning btn-sm m-1 editRecord" data-id="'.$record->id.'">Edit</button>';
                $actions .='<a href="'.url('/activities/'.$record->id.'/edit').'"  type="button" class="btn btn-warning btn-sm m-1"><i class="dropdown-icon lnr-pencil"> </i><span>Edit</span></a>';
            }

            if (!Gate::denies('access',['activities','delete'])){
                $deleteFunction = "deleteRecord(" . "'".$record->id."'". "," ."'".$record->activity_code."'". ")";
                $actions .= '<button  type="button" class="btn btn-danger btn-sm m-1"  onclick="'.$deleteFunction.'" data-id="'.$record->id.'" data-code="'.$record->project_code.'"><i class="dropdown-icon lnr-trash"> </i><span>Delete</span></button>';
            }

            $data_arr[] = array(
                "#" => $no,
                "Activity" => $record->activity_name,
                "Activity Code" => $record->activity_code,
                "Project" => $record->project_name,
                "Project Code" => $record->project_code,
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


        $activity = Activity::find($request->id);

        if(isset($activity->id)){

            $activityId = $activity->id;
            $activity->delete();

            //record user activity
            $activity = [
                'action'=> 'Delete',
                'item'=> 'activities',
                'item_id'=> $activityId,
                'description'=> 'Deleted New Activity With ID - '.$activityId,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);


            return response()->json([
                'feedback' => 'success',
                'message' => 'Activity Deleted Successfully'
            ]);


        }else{
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Oppsss! Unable to find specified Project'
            ]);
        }

    }

}
