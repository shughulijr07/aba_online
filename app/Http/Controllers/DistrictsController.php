<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Region;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class DistrictsController extends Controller
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
        if (Gate::denies('access',['districts','view'])){
            abort(403, 'Access Denied');
        }

        $districts = District::all();

        $model_name = 'district';
        $controller_name = 'districts';
        $view_type = 'index';

        return view('locations.districts.index', compact('districts', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['districts','store'])){
            abort(403, 'Access Denied');
        }

        $countries = Country::all();
        $regions = Region::all();
        $district = new District();
        $country_id = '';
        $region_id = '';

        $model_name = 'district';
        $controller_name = 'districts';
        $view_type = 'create';

        return view('locations.districts.create', compact('countries','regions','district', 'country_id', 'region_id', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['districts','store'])){
            abort(403, 'Access Denied');
        }

        //dd($request);
        $this->validateRequest();

        $country_id = $request->country;
        $region_id = $request->region;
        $district_name = $request->district;

        $district = new District();
        $district->name = $district_name;
        $district->region_id = $region_id;
        $district->save();


        return redirect('districts/'.$district->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function show(District $district)
    {
        if (Gate::denies('access',['districts','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'district';
        $controller_name = 'districts';
        $view_type = 'show';
        return view('locations.districts.show', compact('district', 'model_name', 'controller_name','view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
        if (Gate::denies('access',['districts','edit'])){
            abort(403, 'Access Denied');
        }

        $countries = Country::all();
        $country_id = District::find($district->id)->region->country->id;


        $region = new Region();
        $regions = $region->where('country_id','=',$country_id)->get();
        $region_id = $district->region_id;

        $model_name = 'district';
        $controller_name = 'districts';
        $view_type = 'edit';
        return view('locations.districts.edit', compact('countries','regions','district', 'country_id', 'region_id',  'model_name', 'controller_name','view_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        if (Gate::denies('access',['districts','edit'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $country_id = $request->country;
        $region_id = $request->region;
        $district_name = $request->district;


        $district->name = $district_name;
        $district->region_id = $region_id;
        $district->save();

        return redirect('districts/'.$district->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
        if (Gate::denies('access',['districts','delete'])){
            abort(403, 'Access Denied');
        }

        $district->delete();

        return redirect('districts');
    }


    private function validateRequest(){

        return request()->validate([
            'district' => 'required',
            'region' => 'required|integer',
            'country' => 'required|integer'
        ]);

    }

    public function ajaxGetDistricts(Request $request)
    {

        $districts ='';
        $region_id = $request->region_id;
        if($request->ajax())
        {
            $districts = DB::table('districts')
                ->select('id', 'name')
                ->where('region_id', '=',$region_id)
                ->get();
        }

        echo json_encode($districts);

    }
}
