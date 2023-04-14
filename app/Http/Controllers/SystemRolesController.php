<?php

namespace App\Http\Controllers;

use App\Models\SystemRole;
use App\Models\SystemRolePermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class SystemRolesController extends Controller
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
        if (Gate::denies('access',['system_roles','view'])){
            abort(403, 'Accsess Denied');
        }

        $system_roles = SystemRole::get_system_roles_list();

        $model_name = 'system_role';
        $controller_name = 'system_roles';
        $view_type = 'index';

        return view('system_roles.index', compact('system_roles','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['system_roles','store'])){
            abort(403, 'Access Denied');
        }

        $system_role = new SystemRole();

        $model_name = 'system_role';
        $controller_name = 'system_roles';
        $view_type = 'create';

        return view('system_roles.create',compact( 'system_role','model_name', 'controller_name', 'view_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('access',['system_roles','store'])){
            abort(403, 'Access Denied');
        }

        $system_role = SystemRole::create($this->validateRequest());

        //create permissions for this role
        SystemRolePermissionsController::createPermissionsForOneRole($system_role);

        return redirect('system_roles/'.$system_role->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SystemRole  $systemRole
     * @return \Illuminate\Http\Response
     */
    public function show(SystemRole $systemRole)
    {
        if (Gate::denies('access',['system_roles','view'])){
            abort(403, 'Accsess Denied');
        }

        $system_role = $systemRole;
        $model_name = 'system_role';
        $controller_name = 'system_roles';
        $view_type = 'show';
        $role_permissions = SystemRolePermission::where('system_role_id',$system_role->id)->get();

        return view('system_roles.show',compact( 'system_role', 'role_permissions', 'model_name', 'controller_name', 'view_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SystemRole  $systemRole
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemRole $systemRole)
    {
        if (Gate::denies('access',['system_roles','edit'])){
            abort(403, 'Access Denied');
        }

        $system_role = $systemRole;

        $model_name = 'system_role';
        $controller_name = 'system_roles';
        $view_type = 'edit';

        return view('system_roles.edit',compact( 'system_role','model_name', 'controller_name', 'view_type'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SystemRole  $systemRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemRole $systemRole)
    {
        if (Gate::denies('access',['system_roles','edit'])){
            abort(403, 'Access Denied');
        }

        $systemRole->update($this->validateRequest());

        return redirect('system_roles/'.$systemRole->id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SystemRole  $systemRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemRole $systemRole)
    {
        if (Gate::denies('access',['system_roles','delete'])){
            abort(403, 'Access Denied');
        }

        //delete permissions for this role first
        SystemRolePermissionsController::deletePermissionsForOneRole($systemRole->id);

        $systemRole->delete();

        return redirect('system_roles');
    }


    private function validateRequest(){

        return request()->validate([
            'role_name' => 'required',
        ]);

    }

}
