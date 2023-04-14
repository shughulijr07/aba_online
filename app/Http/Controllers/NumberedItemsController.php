<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NumberedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NumberedItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }




    public function index()
    {
        if (Gate::denies('access',['numbered_items','view'])){
            abort(403, 'Access Denied');
        }

        $numbered_items = NumberedItem::all();

        $model_name = 'numbered_item';
        $controller_name = 'numbered_items';
        $view_type = 'index';

        return view('numbered_items.index',
            compact('numbered_items','model_name', 'controller_name', 'view_type'));
    }



    public function create()
    {
        if (Gate::denies('access',['numbered_items','store'])){
            abort(403, 'Access Denied');
        }

        $numbered_item = new NumberedItem();

        $model_name = 'numbered_item';
        $controller_name = 'numbered_items';
        $view_type = 'create';

        return view('numbered_items.create',
            compact( 'numbered_item','model_name', 'controller_name', 'view_type'));
    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['numbered_items','store'])){
            abort(403, 'Access Denied');
        }

        $numbered_item = NumberedItem::create($this->validateRequest());

        return redirect('numbered_items/'.$numbered_item->id);
    }



    public function show(NumberedItem $numberedItem)
    {
        if (Gate::denies('access',['numbered_items','view'])){
            abort(403, 'Access Denied');
        }

        $numbered_item= $numberedItem;
        $model_name = 'numbered_item';
        $controller_name = 'numbered_items';
        $view_type = 'show';

        return view('numbered_items.show',
            compact( 'numbered_item', 'model_name', 'controller_name', 'view_type'));

    }


    function edit(NumberedItem $numberedItem)
    {
        if (Gate::denies('access',['numbered_items','edit'])){
            abort(403, 'Access Denied');
        }

        $numbered_item = $numberedItem;

        $model_name = 'numbered_item';
        $controller_name = 'numbered_items';
        $view_type = 'edit';

        return view('numbered_items.edit',
            compact( 'numbered_item','model_name', 'controller_name', 'view_type'));

    }



    public function update(Request $request, NumberedItem $numberedItem)
    {
        if (Gate::denies('access',['numbered_items','edit'])){
            abort(403, 'Access Denied');
        }

        $numberedItem->update($this->validateRequest());

        return redirect('numbered_items/'.$numberedItem->id);
    }


    public function destroy(NumberedItem $numberedItem)
    {
        if (Gate::denies('access',['numbered_items','delete'])){
            abort(403, 'Access Denied');
        }

        $numberedItem->delete();

        return redirect('numbered_items');
    }


    private function validateRequest(){

        return request()->validate([
            'name' => 'required',
        ]);

    }
}
