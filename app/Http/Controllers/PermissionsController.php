<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends Controller
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
        if (Gate::denies('access',['permissions','view'])){
            abort(403, 'Access Denied');
        }
        $permissions = Permission::all();

        $model_name = 'permission';
        $controller_name = 'permissions';
        $view_type = 'index';

        return view('permissions.index', compact('permissions','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['permissions','store'])){
            abort(403, 'Access Denied');
        }

        $permission = new Permission();

        $model_name = 'permission';
        $controller_name = 'permissions';
        $view_type = 'create';

        return view('permissions.create',compact( 'permission','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['permissions','store'])){
            abort(403, 'Access Denied');
        }

        $permission = Permission::create($this->validateRequest());

        //add this permission to all system roles
        SystemRolePermissionsController::createPermissionForAllRoles($permission);

        return redirect('permissions/'.$permission->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        if (Gate::denies('access',['permissions','view'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'permission';
        $controller_name = 'permissions';
        $view_type = 'show';

        return view('permissions.show',compact( 'permission','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (Gate::denies('access',['permissions','edit'])){
            abort(403, 'Access Denied');
        }

        $permission = $permission;

        $model_name = 'permission';
        $controller_name = 'permissions';
        $view_type = 'edit';

        return view('permissions.edit',compact( 'permission','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (Gate::denies('access',['permissions','edit'])){
            abort(403, 'Access Denied');
        }


        $permission->update($this->validateRequest());


        return redirect('permissions/'.$permission->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (Gate::denies('access',['permissions','delete'])){
            abort(403, 'Access Denied');
        }

        $permission->delete();

        //delete this permission from all roles
        SystemRolePermissionsController::deletePermissionForAllRoles($permission);

        return redirect('permissions');
    }


    private function validateRequest(){

        return request()->validate([
            'permission_name' => 'required',
            'category' => 'required',
            'description' => '',
        ]);

    }
}
