<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\MyFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HolidaysController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['holidays','view'])){
            abort(403, 'Access Denied');
        }

        $holidays = Holiday::all();
        $year = "All";

        $model_name = 'holiday';
        $controller_name = 'holidays';
        $view_type = 'index';

        return view('holidays.index',
            compact('holidays', 'year','model_name', 'controller_name', 'view_type'));
    }

    public function index2($year)
    {
        if (Gate::denies('access',['holidays','view'])){
            abort(403, 'Access Denied');
        }


        $holidays = Holiday::where('holiday_year','=',$year)->get();

        $model_name = 'holiday';
        $controller_name = 'holidays';
        $view_type = 'index';

        return view('holidays.index',
            compact('holidays', 'year','model_name', 'controller_name', 'view_type'));
    }



    public function create()
    {
        if (Gate::denies('access',['departments','store'])){
            abort(403, 'Access Denied');
        }

        $holiday = new Holiday();
        $holiday_date = '';

        $model_name = 'holiday';
        $controller_name = 'holidays';
        $view_type = 'create';

        return view('holidays.create',
            compact( 'holiday', 'holiday_date','model_name', 'controller_name', 'view_type'));
    }



    public function store(Request $request)
    {
        if (Gate::denies('access',['holidays','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();

        $holiday = new Holiday();
        $holiday->holiday_year = $data['holiday_year'];
        $holiday->name = $data['name'];
        $holiday->holiday_date = MyFunctions::convert_date_to_mysql_format( $data['holiday_date']);
        $holiday->save();

        return redirect('holidays/'.$holiday->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        if (Gate::denies('access',['holidays','view'])){
            abort(403, 'Access Denied');
        }


        $model_name = 'holiday';
        $controller_name = 'holidays';
        $view_type = 'show';

        return view('holidays.show',
            compact( 'holiday','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        if (Gate::denies('access',['holidays','edit'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'holiday';
        $controller_name = 'holidays';
        $view_type = 'edit';

        $holiday_date = date('d/m/Y',strtotime($holiday->holiday_date));

        return view('holidays.edit',
            compact( 'holiday','holiday_date','model_name', 'controller_name', 'view_type'));

    }




    public function update(Request $request, Holiday $holiday)
    {
        if (Gate::denies('access',['holidays','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();

        $holiday->holiday_year = $data['holiday_year'];
        $holiday->name = $data['name'];
        $holiday->holiday_date = MyFunctions::convert_date_to_mysql_format( $data['holiday_date']);
        $holiday->save();

        return redirect('holidays/'.$holiday->id);

   }



    public function destroy(Holiday $holiday)
    {
        if (Gate::denies('access',['holidays','delete'])){
            abort(403, 'Access Denied');
        }

        $holiday->delete();

        return redirect('holidays');

    }


    private function validateRequest(){

        return request()->validate([
            'holiday_year' => 'required',
            'name' => 'required',
            'holiday_date' => 'required',
        ]);

    }
}
