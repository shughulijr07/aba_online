<?php

namespace App\Http\Controllers;

use App\Models\CompanyInformation;
use App\Models\Country;
use App\Models\District;
use App\Models\Region;
use App\Models\Ward;
use Illuminate\Http\Request;
use Gate;

class CompanyInformationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show(CompanyInformation $companyInformation)
    {/*
        if (Gate::denies('access',['company_information','create'])){
            abort(403, 'Access Denied');
        }*/

        //check if company information have been added previously
        $company_info = CompanyInformation::find(1);

        if( isset($company_info->id) ){
            //do nothing
        }else{
            //set up new company information
            $company_info = new CompanyInformation();
            $company_info->company_name = '';
            $company_info->save();
        }

        $countries = Country::all();
        $regions = Region::all();
        $districts = District::all();
        $wards = Ward::all();



        $country_id = $company_info->country_id;
        $region_id = $company_info->region_id;
        $district_id = $company_info->district_id;
        $ward_id = $company_info->ward_id;
        $ward_id = $company_info->ward_id;

        $model_name = 'company_information';
        $controller_name = 'company_information';
        $view_type = 'create';

        return view('company_information.show',
            compact('company_info','countries', 'regions', 'districts', 'wards',  'country_id', 'region_id',
                'district_id', 'ward_id',
                'model_name', 'controller_name', 'view_type'));
    }



    public function update(Request $request)
    {
        $data  = $this->validateRequest();
        //dd($data);
        $company_info = CompanyInformation::find(1);
        $company_info->update($data);

        //Update Image
        $this->storeImage($company_info);

        return redirect('company_information');
    }


    private function storeImage($company_info){

        if ( request()->has('image') ){
            $company_info->update([
                'logo' => request()->logo->store('uploads','public'),
            ]);
        }

    }


    private function validateRequest(){ //dd(request());

        return $data = request()->validate([
            'logo' => 'sometimes|file|image|max:1000',
            'company_name' => 'required',
            'physical_address' => 'nullable',
            'postal_address' => 'nullable',
            'house_no' => 'nullable',
            'plot_no' => 'nullable',
            'street_name' => 'nullable',
            'office_phone_no1' => 'nullable',
            'office_phone_no2' => 'nullable',
            'fax_no' => 'nullable',
            'email' => 'nullable|E-mail',
            'website' => 'nullable',
            'country_id' => 'nullable',
            'region_id' => 'nullable',
            'district_id' => 'nullable',
            'ward_id' => 'nullable',

        ]);

    }
}
