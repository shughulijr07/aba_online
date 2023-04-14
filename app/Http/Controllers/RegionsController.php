<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('access',['regions','view'])){
            abort(403, 'Access Denied');
        }

        $regions = Region::all();

        $model_name = 'region';
        $controller_name = 'regions';
        $view_type = 'index';

        return view('locations.regions.index', compact('regions', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['regions','store'])){
            abort(403, 'Access Denied');
        }

        $countries = Country::all();
        $region = new Region();

        $model_name = 'region';
        $controller_name = 'regions';
        $view_type = 'create';

        return view('locations.regions.create', compact('countries','region', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['regions','store'])){
            abort(403, 'Access Denied');
        }

        $this->validateRequest();

        $region_name = $request->name;
        $country_id =  $request->country_id;

        $region = new Region();
        $region->name = $region_name;
        $region->country_id = $country_id;
        $region->save();

        return redirect('regions/'.$region->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        if (Gate::denies('access',['regions','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'region';
        $controller_name = 'regions';
        $view_type = 'show';
        return view('locations.regions.show', compact('region', 'model_name', 'controller_name','view_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        if (Gate::denies('access',['regions','edit'])){
            abort(403, 'Access Denied');
        }

        $countries = Country::all();
        $model_name = 'region';
        $controller_name = 'regions';
        $view_type = 'edit';
        return view('locations.regions.edit', compact('countries','region', 'model_name', 'controller_name','view_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        if (Gate::denies('access',['regions','edit'])){
            abort(403, 'Access Denied');
        }

        $region->update($this->validateRequest());

        return redirect('regions/'.$region->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        if (Gate::denies('access',['regions','delete'])){
            abort(403, 'Access Denied');
        }

        $region->delete();

        return redirect('regions');
    }



    private function validateRequest(){

        return request()->validate([
            'name' => 'required',
            'country_id' => 'required|integer'
         ]);

    }

    public function ajaxGetRegions(Request $request)
    {

          $regions ='';
          $country_id = $request->country_id;
          if($request->ajax())
          {
              $regions = DB::table('regions')
                            ->select('id', 'name')
                            ->where('country_id', '=',$country_id)
                            ->get();
          }

          echo json_encode($regions);
    }

}
