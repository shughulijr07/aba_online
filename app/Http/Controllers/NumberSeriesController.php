<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NumberedItem;
use App\Models\NumberSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NumberSeriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        if (Gate::denies('access',['number_series','view'])){
            abort(403, 'Access Denied');
        }

        $number_series = NumberSeries::all();

        $model_name = 'number_series';
        $controller_name = 'number_series';
        $view_type = 'index';

        return view('number_series.index',
            compact('number_series','model_name', 'controller_name', 'view_type'));
    }



    public function create()
    {
        if (Gate::denies('access',['number_series','store'])){
            abort(403, 'Access Denied');
        }

        $number_series = new NumberSeries();
        $existing_number_series = NumberSeries::all();

        $numbered_items = NumberedItem::all();
        $include_year = 'Yes';
        $include_month = 'Yes';
        $starting_no = 1;
        $increment_by = 1;
        $reset_on = 'Year';
        $number_of_digits = '4';
        $separator = 'blank';

        $model_name = 'number_series';
        $controller_name = 'number_series';
        $view_type = 'create';

        return view('number_series.create',
            compact( 'number_series', 'existing_number_series','numbered_items', 'include_year','include_month',
                'starting_no', 'increment_by','number_of_digits','reset_on', 'separator',
                'model_name', 'controller_name', 'view_type'));

    }



    public function store(Request $request)
    {
        if (Gate::denies('access',['number_series','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateRequest();

        $numbered_item_id =  $data['numbered_item_id'];
        $abbreviation = ucwords($data['abbreviation']);
        $include_month = $data['include_month'];
        $include_year = $data['include_year'];
        $starting_no =  $data['starting_no'];
        $increment_by = $data['increment_by'];
        $number_of_digits = $data['number_of_digits'];
        $reset_on = $data['reset_on'];
        $last_no_used = 0;
        $separator = $data['separator'];


        $formatted_starting_no =  str_pad($starting_no, $number_of_digits, '0', STR_PAD_LEFT);
        $first_no_used_code = '';
        $last_no_used_code = '';

        $number_series = new NumberSeries();
        $number_series->numbered_item_id =  $numbered_item_id;
        $number_series->abbreviation = $abbreviation;
        $number_series->year = date('Y');
        $number_series->month = date('m');
        $number_series->include_year = $include_year;
        $number_series->include_month = $include_month;
        $number_series->starting_no = $starting_no; //save original value, don't modify it
        $number_series->increment_by = $increment_by;
        $number_series->last_no_used = $last_no_used;
        $number_series->number_of_digits = $number_of_digits;
        $number_series->reset_on = $reset_on;
        $number_series->first_no_used_code = $first_no_used_code;
        $number_series->last_no_used_code = $last_no_used_code;
        $number_series->separator = $separator;
        $number_series->save();


        return redirect('number_series/'.$number_series->id);
    }



    public function show(NumberSeries $numberSeries)
    {
        if (Gate::denies('access',['number_series','view'])){
            abort(403, 'Access Denied');
        }

        $number_series = $numberSeries;
        $model_name = 'number_series';
        $controller_name = 'number_series';
        $view_type = 'show';

        return view('number_series.show',
            compact( 'number_series', 'model_name', 'controller_name', 'view_type'));

    }


    public function edit(NumberSeries $numberSeries)
    {
        if (Gate::denies('access',['number_series','edit'])){
            abort(403, 'Access Denied');
        }

        $number_series = $numberSeries;
        $existing_number_series = NumberSeries::all();

        $numbered_items = NumberedItem::all();
        $include_year = $number_series->include_year;
        $include_month = $number_series->include_month;
        $starting_no = $number_series->starting_no;
        $increment_by = $number_series->increment_by;
        $number_of_digits = $number_series->number_of_digits;
        $reset_on = $number_series->reset_on;
        $separator = $number_series->separator;

        $model_name = 'number_series';
        $controller_name = 'number_series';
        $view_type = 'edit';

        return view('number_series.edit',
            compact( 'number_series', 'existing_number_series','numbered_items', 'include_year',
                'include_month','starting_no', 'increment_by','number_of_digits','reset_on', 'separator',
                'model_name', 'controller_name', 'view_type'));

    }


    public function update(Request $request, NumberSeries $numberSeries)
    {
        if (Gate::denies('access',['number_series','edit'])){
            abort(403, 'Access Denied');
        }

        //updating number series means closing number series which was in use and creating brand new number series

        $data = $this->validateRequest();

        $numbered_item_id =  $data['numbered_item_id'];
        $abbreviation = ucwords($data['abbreviation']);
        $include_year = $data['include_year'];
        $include_month = $data['include_month'];
        $number_of_digits = $data['number_of_digits'];
        $starting_no =  $data['starting_no'];
        $formatted_starting_no =  str_pad($starting_no, $number_of_digits, '0', STR_PAD_LEFT);
        $last_no_used = 0;
        $increment_by = $data['increment_by'];
        $reset_on = $data['reset_on'];
        $first_no_used_code = '';
        $last_no_used_code = '';
        $separator = $data['separator'];

        $number_series = $numberSeries;
        $number_series->numbered_item_id =  $numbered_item_id;
        $number_series->abbreviation = $abbreviation;
        $number_series->year = date('Y');
        $number_series->month = date('m');
        $number_series->include_year = $include_year;
        $number_series->include_month = $include_month;
        $number_series->starting_no = $starting_no;//save original value, don't modify it
        $number_series->increment_by = $increment_by;
        $number_series->last_no_used = $last_no_used;
        $number_series->number_of_digits = $number_of_digits;
        $number_series->reset_on = $reset_on;
        $number_series->first_no_used_code = $first_no_used_code;
        $number_series->last_no_used_code = $last_no_used_code;
        $number_series->separator = $separator;
        $number_series->save();


        return redirect('number_series/'.$number_series->id);
    }


    public function destroy(NumberSeries $numberSeries)
    {
        if (Gate::denies('access',['number_series','delete'])){
            abort(403, 'Access Denied');
        }

        $numberSeries->delete();

        return redirect('number_series');

    }


    private function validateRequest(){

        return request()->validate([
            'numbered_item_id' => 'required',
            'abbreviation' => 'required',
            'include_year' => 'required',
            'include_month' => 'required',
            'starting_no' => 'required|integer|min:0',
            'increment_by' => 'required|integer|min:0',
            'number_of_digits' => 'required|integer|min:3',
            'reset_on' => 'required',
            'separator' => 'required',
        ]);

    }

}
