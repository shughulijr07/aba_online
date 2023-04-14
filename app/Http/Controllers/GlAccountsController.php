<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\CompanyInformation;
use App\Models\GlAccount;
use App\Imports\ExcelImport;
use App\Models\Project;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GlAccountsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['gl_accounts','view'])){
            abort(403, 'Access Denied');
        }

        $companyLogoBase64 = CompanyInformation::$companyLogoBase64;
        $companyLogoPath = url(CompanyInformation::$companyLogoLocation);

        $model_name = 'gl_account';
        $controller_name = 'gl_accounts';
        $reportTitle = 'PROJECTS';
        $view_type = 'index';

        return view('gl_accounts.index',
            compact('companyLogoBase64','companyLogoPath',
                'reportTitle', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['gl_accounts','store'])){
            abort(403, 'Access Denied');
        }

        $gl_account = new GlAccount();

        $model_name = 'gl_account';
        $controller_name = 'gl_accounts';
        $view_type = 'create';

        return view('gl_accounts.create',
            compact( 'gl_account','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['gl_accounts','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $gl_account = new GlAccount();
        $gl_account->number = $request->number;
        $gl_account->name = $request->name;
        $gl_account->save();

        return redirect('gl_accounts/'.$gl_account->id);

    }



    public function importFromExcel() //We will modify this function letter the process of uploading file can be done from interface
    {
        if (Gate::denies('access',['gl_accounts','store'])){
            abort(403, 'Access Denied');
        }

        ini_set('max_execution_time', '60000');

        $filename = 'codes.xlsx';
        $file_path = public_path().'/storage/imports/'.$filename;
        $importedExcel = (new ExcelImport)->toArray($file_path);
        $importedExcelRows = $importedExcel[2];

        $n = 0;
        foreach($importedExcelRows as $importedExcelRow){
            $n++; if($n == 1){ continue; } //Skip first row with titles

            $glAccountNumber = strtoupper(strtolower($importedExcelRow[0]));
            $glAccountName = $importedExcelRow[1];

            $existingGlAccount = GlAccount::where('number', '=', $glAccountNumber)->first();

            if(!isset($existingGlAccount->id)){
                $glAccount = new GlAccount();
                $glAccount->name = $glAccountName;
                $glAccount->number = $glAccountNumber;
                $glAccount->save();
            }

        }


        return redirect('gl_accounts');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\GlAccount  $gl_account
     * @return \Illuminate\Http\Response
     */
    public function show(GlAccount $gl_account)
    {
        if (Gate::denies('access',['gl_accounts','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'gl_account';
        $controller_name = 'gl_accounts';
        $view_type = 'show';

        return view('gl_accounts.show',
            compact( 'gl_account','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GlAccount  $gl_account
     * @return \Illuminate\Http\Response
     */
    public function edit(GlAccount $gl_account)
    {
        if (Gate::denies('access',['gl_accounts','edit'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'gl_account';
        $controller_name = 'gl_accounts';
        $view_type = 'edit';

        return view('gl_accounts.edit',
            compact( 'gl_account','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GlAccount  $gl_account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GlAccount $gl_account)
    {
        if (Gate::denies('access',['gl_accounts','edit'])){
            abort(403, 'Access Denied');
        }

        $gl_account->update($this->validateRequest());

        return redirect('gl_accounts/'.$gl_account->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GlAccount  $gl_account
     * @return \Illuminate\Http\Response
     */
    public function destroy(GlAccount $gl_account)
    {
        if (Gate::denies('access',['gl_accounts','delete'])){
            abort(403, 'Access Denied');
        }

        $gl_account->delete();

        return redirect('gl_accounts');
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

        $searchTable = "gl_accounts";

        //Convert Column Names
        if($columnName == "#"){$columnName = "id";}
        if($columnName == "GL Account Name"){$columnName = "name"; $searchTable = "gl_accounts";}
        if($columnName == "GL Account Number"){$columnName = "number"; $searchTable = "gl_accounts";}
        if($columnName == "Actions"){$columnName = "id";}



        $exactMatchColumns = [ 'id'];

        $records = [];
        $totalRecords = GlAccount::select('count(*) as allcount');
        $totalRecordswithFilter = 0;


        $tableQuery = DB::table('gl_accounts');

        $fieldQuery = $tableQuery->select(
            'gl_accounts.id as id',
            'gl_accounts.name as gl_account_name',
            'gl_accounts.number as gl_account_number'
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
            $actions ='<a href="'.url('/gl_accounts/'.$record->id).'" type="button" class="btn btn-success btn-sm m-1"><i class="dropdown-icon lnr-eye"> </i><span>View</span></a>';


            if (!Gate::denies('access',['gl_accounts','edit'])){
                //$actions .= '<button  type="button" class="btn btn-warning btn-sm m-1 editRecord" data-id="'.$record->id.'">Edit</button>';
                $actions .='<a href="'.url('/gl_accounts/'.$record->id.'/edit').'"  type="button" class="btn btn-warning btn-sm m-1"><i class="dropdown-icon lnr-pencil"> </i><span>Edit</span></a>';
            }

            if (!Gate::denies('access',['gl_accounts','delete'])){
                $deleteFunction = "deleteRecord(" . "'".$record->id."'". "," ."'".$record->gl_account_number."'". ")";
                $actions .= '<button  type="button" class="btn btn-danger btn-sm m-1"  onclick="'.$deleteFunction.'" data-id="'.$record->id.'" data-number="'.$record->gl_account_number.'"><i class="dropdown-icon lnr-trash"> </i><span>Delete</span></button>';
            }

            $data_arr[] = array(
                "#" => $no,
                "GL Account Number" => $record->gl_account_number,
                "GL Account Name" => $record->gl_account_name,
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

        if (Gate::denies('access',['gl_accounts','store'])){
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Not Authorized!'
            ]);
        }


        $gl_account = GlAccount::find($request->id);

        if(isset($gl_account->id)){

            $activities = $gl_account->activities;
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

            $gl_accountId = $gl_account->id;
            $gl_account->delete();

            //record user activity
            $activity = [
                'action'=> 'Delete',
                'item'=> 'gl_accounts',
                'item_id'=> $gl_accountId,
                'description'=> 'Deleted New GlAccount With ID - '.$gl_accountId,
                'user_id'=> auth()->user()->id,
            ];

            $activity_category = 'major';
            UserActivity::record_user_activity($activity, $activity_category);


            return response()->json([
                'feedback' => 'success',
                'message' => 'GlAccount Deleted Successfully'
            ]);


        }else{
            return response()->json([
                'feedback' => 'fail',
                'message' => 'Oppsss! Unable to find specified GlAccount'
            ]);
        }

    }

}
