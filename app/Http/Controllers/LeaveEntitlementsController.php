<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\LeaveEntitlement;
use App\Models\LeaveEntitlementExtension;
use App\Models\LeaveEntitlementLine;
use App\Models\LeaveType;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeaveEntitlementsController extends Controller
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
        if (Gate::denies('access',['leave_entitlements','view'])){
            abort(403, 'Access Denied');
        }

        //before anything generate leave entitlement automatically, call the function and it will take care of the rest
        $entitlement = new LeaveEntitlement();
        $entitlement->create_leave_entitlement_for_all_staff(date('Y')); // if entitlement exists it will be skipped

        //then do carry over if carry over mode is automatic
        //get system settings
        $system_settings = GeneralSetting::find(1);
        $carry_over_mode = $system_settings->carry_over_mode;
        if( $carry_over_mode== 1 ){//automatic mode
            $entitlement->make_carry_over_for_all_staff();
        }

        $leave_entitlements = LeaveEntitlement::where('year','=',date('Y'))->get();
        //$leave_entitlements = LeaveEntitlement::all();

        $model_name = 'leave_entitlement';
        $controller_name = 'leave_entitlements';
        $view_type = 'index';

        return view('leave_entitlements.index',
            compact('leave_entitlements','carry_over_mode',
                     'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['leave_entitlements','store'])){
            abort(403, 'Access Denied');
        }

        $leave_entitlement = new LeaveEntitlement();
        $all_staff = Staff::get_valid_staff_list();
        $staff_id = '';
        $year = date('Y');
        $initial_year = 2019;
        $leave_types = LeaveType::where('status','=','Active')->get();

        $model_name = 'leave_entitlement';
        $controller_name = 'leave_entitlements';
        $view_type = 'create';

        return view('leave_entitlements.create',
            compact( 'leave_entitlement','all_staff','staff_id','year','initial_year', 'leave_types',
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
        if (Gate::denies('access',['leave_entitlements','store'])){
            abort(403, 'Access Denied');
        }

        //validate required fields
        $data = $this->validateRequest();
        $staff_id = $data['staff_id'];
        $year = $data['year'];


        //get all fields submitted
        $all_data = $request->all();
        $leave_entitlement_lines = array_splice($all_data, 3);

        $leave_types = LeaveType::where('status','=','Active')->get();

        //check if entitlement exist
        $entitlement = LeaveEntitlement::where('staff_id','=',$staff_id)->where('year','=',$year)->first();

        if( isset($entitlement->id) ){
            //do nothing
        }else{

            $entitlement = new LeaveEntitlement();
            $entitlement->staff_id = $staff_id;
            $entitlement->year = $year;
            $entitlement->save();

            //save entitlement lines
            foreach ($leave_entitlement_lines as $leave_type => $days){

                $line = new LeaveEntitlementLine();
                $line->leave_entitlement_id = $entitlement->id;
                $line->type_of_leave = $leave_type;
                $line->number_of_days = $days;
                $line->save();

                foreach ( $leave_types as $leave){

                    if( $leave->key == $leave_type ){


                        //check if number of days provided exceed default,
                        // if they exceed, or they are less then record extra days as extension of leave
                        if($leave->days != $days){
                            //record the extension
                            $extended_days = $days - $leave->days;

                            $annual_leave_extension = new LeaveEntitlementExtension();
                            $annual_leave_extension->leave_entitlement_line_id = $line->id;
                            $annual_leave_extension->no_days = $extended_days;
                            $annual_leave_extension->done_by = auth()->user()->staff->id;
                            $annual_leave_extension->reason = 'Created automatically due extra number of days during entitlement';
                            $annual_leave_extension->save();

                        }

                    }

                }

            }

        }


        return redirect('leave_entitlements');
        // return redirect('leave_entitlement/'.$entitlement->id);
    }



    public function show($id)
    {
        if (Gate::denies('access',['leave_entitlements','view'])){
            abort(403, 'Access Denied');
        }


        $system_settings = GeneralSetting::find(1);
        $carry_over_mode = $system_settings->carry_over_mode;

        $leave_entitlement = LeaveEntitlement::find($id);
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $model_name = 'leave_entitlement';
        $controller_name = 'leave_entitlements';
        $view_type = 'show';

        return view('leave_entitlements.show',
            compact( 'leave_entitlement','leave_types','carry_over_mode',
                'model_name', 'controller_name', 'view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LeaveEntitlement  $leaveEntitlement
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('access',['leave_entitlements','edit'])){
            abort(403, 'Access Denied');
        }

        $leave_entitlement = LeaveEntitlement::find($id);
        $all_staff = Staff::get_valid_staff_list();
        $staff_id = $leave_entitlement->staff->id;
        $year = $leave_entitlement->year;
        $initial_year = 2019;
        $leave_types = LeaveType::get_active_leave_types();

        $model_name = 'leave_entitlement';
        $controller_name = 'leave_entitlements';
        $view_type = 'edit';

        return view('leave_entitlements.edit',
            compact( 'leave_entitlement','all_staff','staff_id','year','initial_year', 'leave_types',
                'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LeaveEntitlement  $leaveEntitlement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $leave_entitlement = LeaveEntitlement::find($id);

        //validate required fields
        $data = $this->validateRequest();
        $staff_id = $data['staff_id'];
        $year = $data['year'];


        //get all fields submitted
        $all_data = $request->all();
        $leave_entitlement_lines = array_splice($all_data, 4);

        //update entitlement lines
        foreach ($leave_entitlement_lines as $leave_type => $days){

            $line = LeaveEntitlementLine::where('leave_entitlement_id','=',$leave_entitlement->id)
                                        ->where('type_of_leave','=',$leave_type)->first();


            $old_line_days = $line->number_of_days;

            //update days in line
            $line->number_of_days = $days;
            $line->save();


            //check if number of days in the line have changed
            // if yes, record the change in entitlement extensions
            if($old_line_days != $days){
                //record the extension
                $extended_days = $days - $old_line_days;

                $annual_leave_extension = new LeaveEntitlementExtension();
                $annual_leave_extension->leave_entitlement_line_id = $line->id;
                $annual_leave_extension->no_days = $extended_days;
                $annual_leave_extension->done_by = auth()->user()->staff->id;
                $annual_leave_extension->reason = 'Created automatically due extra number of days during entitlement';
                $annual_leave_extension->save();

            }


        }



        return redirect('leave_entitlements/'.$leave_entitlement->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LeaveEntitlement  $leaveEntitlement
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveEntitlement $leaveEntitlement)
    {
        //
    }


    public function performCarryOver($staff_id){


        if (Gate::denies('access',['leave_entitlements','store'])){
            abort(403, 'Access Denied');
        }

        $entitlement = new LeaveEntitlement();

        if( $staff_id == 'all' ){
            $entitlement->make_carry_over_for_all_staff();
        }else{
            $entitlement->make_carry_over_for_one_staff($staff_id);
        }

        $message = 'Carry over from year '.(date('Y') - 1).' to year '.date('Y').' was completed successfully';
        return redirect()->back()->with('message',$message);


    }


    private function validateRequest(){

        $data =  request()->validate([
            'staff_id' =>  'required|numeric',
            'year' =>  'required|numeric',
        ]);

        return $data;

    }
}
