<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Region;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WardsController extends Controller
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
        if (Gate::denies('access',['wards','view'])){
            abort(403, 'Accsess Denied');
        }

        $wards = Ward::all();

        $model_name = 'ward';
        $controller_name = 'wards';
        $view_type = 'index';

        return view('locations.wards.index', compact('wards', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['wards','store'])){
            abort(403, 'Accsess Denied');
        }

        $countries = Country::all();
        $regions = Region::all();
        $districts = District::all();
        $ward = new Ward();
        $country_id = '';
        $region_id = '';
        $district_id = '';

        $model_name = 'ward';
        $controller_name = 'wards';
        $view_type = 'create';

        return view('locations.wards.create', compact('countries','regions','districts','ward', 'country_id', 'region_id', 'district_id', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['wards','store'])){
            abort(403, 'Accsess Denied');
        }

        //dd($request);
        $this->validateRequest();

        $country_id = $request->country;
        $region_id = $request->region;
        $district_id = $request->district;
        $ward_name = $request->ward;

        $ward = new Ward();
        $ward->name = $ward_name;
        $ward->district_id = $district_id;
        $ward->save();


        return redirect('wards/'.$ward->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function show(Ward $ward)
    {
        if (Gate::denies('access',['wards','view'])){
            abort(403, 'Accsess Denied');
        }

        $model_name = 'ward';
        $controller_name = 'wards';
        $view_type = 'show';
        return view('locations.wards.show', compact('ward', 'model_name', 'controller_name','view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function edit(Ward $ward)
    {
        if (Gate::denies('access',['wards','edit'])){
            abort(403, 'Accsess Denied');
        }

        $countries = Country::all();
        $country_id = Ward::find($ward->id)->district->region->country->id;
        $region_id = Ward::find($ward->id)->district->region->id;

        $regions = Region::all();


        $district = new District();
        $districts = $district->where('region_id','=',$region_id)->get();
        $district_id = $ward->district_id;

        $model_name = 'ward';
        $controller_name = 'wards';
        $view_type = 'edit';
        return view('locations.wards.edit', compact('countries','regions','districts','ward', 'country_id', 'region_id', 'district_id',  'model_name', 'controller_name','view_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ward $ward)
    {
        if (Gate::denies('access',['wards','edit'])){
            abort(403, 'Accsess Denied');
        }

        $this->validateRequest();

        $country_id = $request->country;
        $region_id = $request->region;
        $district_id = $request->district;
        $ward_name = $request->ward;


        $ward->name = $ward_name;
        $ward->district_id = $district_id;
        $ward->save();

        return redirect('wards/'.$ward->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ward  $ward
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ward $ward)
    {
        if (Gate::denies('access',['wards','delete'])){
            abort(403, 'Accsess Denied');
        }

        $ward->delete();

        return redirect('wards');
    }


    private function validateRequest(){

        return request()->validate([
            'ward' => 'required',
            'district' => 'required|integer',
            'region' => 'required|integer',
            'country' => 'required|integer'
        ]);

    }

    public function ajaxGetWards(Request $request)
    {

        $wards ='';
        $district_id = $request->district_id;
        if($request->ajax())
        {
            $wards = DB::table('wards')
                ->select('id', 'name')
                ->where('district_id', '=',$district_id)
                ->get();
        }

        echo json_encode($wards);

    }

}
