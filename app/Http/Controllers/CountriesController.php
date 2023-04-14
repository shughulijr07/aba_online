<?php

namespace App\Http\Controllers;

use App\Models\AfricanCountry;
use App\Models\country;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;
use Illuminate\Support\Facades\Gate; //changed

class CountriesController extends Controller
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
        if (Gate::denies('access',['countries','view'])){
            abort(403, 'Access Denied');
        }

        $countries = Country::all();

        $model_name = 'country';
        $controller_name = 'countries';
        $view_type = 'index';

        return view('locations.countries.index', compact('countries', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['countries','store'])){
            abort(403, 'Access Denied');
        }

        $africanCountries = AfricanCountry::all();
        $country = new Country();

        $model_name = 'country';
        $controller_name = 'countries';
        $view_type = 'create';

        return view('locations.countries.create', compact('africanCountries','country', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['countries','store'])){
            abort(403, 'Access Denied');
        }

       $this->validateRequest();

       $country_name = $request->name;

       $country = new Country();
       $country->name = $country_name;
       $country->save();

       return redirect('countries/'.$country->id);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(country $country)
    {
        if (Gate::denies('access',['countries','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'country';
        $controller_name = 'countries';
        $view_type = 'show';


        return view('locations.countries.show', compact('country', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(country $country)
    {
        if (Gate::denies('access',['countries','edit'])){
            abort(403, 'Access Denied');
        }

        $africanCountries = AfricanCountry::all();
        $model_name = 'country';
        $controller_name = 'countries';
        $view_type = 'edit';
        
        return view('locations.countries.edit', compact('africanCountries','countries','country', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, country $country)
    {
        if (Gate::denies('access',['countries','edit'])){
            abort(403, 'Access Denied');
        }

        $country->update($this->validateRequest());

        return redirect('countries/'.$country->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy(country $country)
    {
        if (Gate::denies('access',['countries','delete'])){
            abort(403, 'Access Denied');
        }

        $country->delete();

        return redirect('countries');
    }



    private function validateRequest(){

        return request()->validate([
            'name' => 'required'
        ]);

    }
    
}
