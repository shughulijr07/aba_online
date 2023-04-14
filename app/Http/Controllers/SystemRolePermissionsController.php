<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\SystemRole;
use App\Models\SystemRolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SystemRolePermissionsController extends Controller
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
        if (Gate::denies('access',['system_role_permissions','view'])){
            abort(403, 'Access Denied');
        }

        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Gate::denies('access',['system_role_permissions','store'])){
            abort(403, 'Access Denied');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if (Gate::denies('access',['system_role_permissions','store'])){
            abort(403, 'Access Denied');
        }

    }

    public static function createPermissionsForOneRole($system_role)
    {
        if (Gate::denies('access',['system_role_permissions','store'])){
            abort(403, 'Access Denied');
        }

        $permissions = Permission::all();
            foreach ($permissions as $permission){
                $system_role_id = $system_role->id;
                $permission_id = $permission->id;

                //check if permission exists
                $count = SystemRolePermission::where('system_role_id',$system_role_id)
                    ->where('permission_id',$permission_id)->get()->count();

                if ($count == 0){ // add permission entry only if it doesn't exist

                    $system_role_permission = new SystemRolePermission();
                    $system_role_permission->system_role_id = $system_role_id;
                    $system_role_permission->permission_id = $permission_id;
                    $system_role_permission->view = 'false';
                    $system_role_permission->add = 'false';
                    $system_role_permission->edit = 'false';
                    $system_role_permission->delete = 'false';
                    $system_role_permission->save();

                }
                else{

                }
            }
    }

    public static function deletePermissionForAllRoles($permission)
    {
        if (Gate::denies('access',['system_role_permissions','delete'])){
            abort(403, 'Access Denied');
        }

        DB::table('system_role_permissions')->where('permission_id',$permission->id)->delete();
    }

    public static function deletePermissionsForOneRole($system_role_id)
    {
        if (Gate::denies('access',['system_role_permissions','delete'])){
            abort(403, 'Access Denied');
        }

        DB::table('system_role_permissions')->where('system_role_id',$system_role_id)->delete();
    }

    public static function createPermissionForAllRoles($permission)
    {
        if (Gate::denies('access',['system_role_permissions','store'])){
            abort(403, 'Access Denied');
        }

        $permission_id = $permission->id;

        $system_roles = SystemRole::get_system_roles_list();

        foreach ($system_roles as $system_role){

            $system_role_id = $system_role->id;

            //check if permission exists
            $count = SystemRolePermission::where('system_role_id',$system_role_id)
                ->where('permission_id',$permission_id)->get()->count();

            if ($count == 0){ // add permission entry only if it doesn't exist

                $system_role_permission = new SystemRolePermission();
                $system_role_permission->system_role_id = $system_role_id;
                $system_role_permission->permission_id = $permission_id;
                $system_role_permission->view = 'false';
                $system_role_permission->add = 'false';
                $system_role_permission->edit = 'false';
                $system_role_permission->delete = 'false';
                $system_role_permission->save();

            }
            else{

            }

        }
    }

    public static function createAllPermissionsForAllRoles()
    {
        if (Gate::denies('access',['system_role_permissions','store'])){
            abort(403, 'Access Denied');
        }

        $system_roles = SystemRole::get_system_roles_list();
        $permissions = Permission::all();
        $n = 1;

        foreach ($system_roles as $system_role){

            foreach ($permissions as $permission){
                $system_role_id = $system_role->id;

                //check if permission exists
                $count = SystemRolePermission::where('system_role_id',$system_role_id)
                    ->where('permission_id',$permission->id)->get()->count();

                if ($count == 0){ // add permission entry only if it doesn't exist

                    $system_role_permission = new SystemRolePermission();
                    $system_role_permission->system_role_id = $system_role_id;
                    $system_role_permission->permission_id = $permission->id;
                    $system_role_permission->view = 'false';
                    $system_role_permission->add = 'false';
                    $system_role_permission->edit = 'false';
                    $system_role_permission->delete = 'false';
                    $system_role_permission->save();

                }
                else{
                   // echo $n.' - Existing <br>';
                }
                $n++;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SystemRolePermission  $systemRolePermission
     * @return \Illuminate\Http\Response
     */
    public function show(SystemRolePermission $systemRolePermission)
    {
        if (Gate::denies('access',['system_role_permissions','view'])){
            abort(403, 'Access Denied');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SystemRolePermission  $systemRolePermission
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemRolePermission $systemRolePermission)
    {
        if (Gate::denies('access',['system_role_permissions','edit'])){
            abort(403, 'Access Denied');
        }

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SystemRolePermission  $systemRolePermission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemRolePermission $systemRolePermission)
    {
        if (Gate::denies('access',['system_role_permissions','edit'])){
            abort(403, 'Access Denied');
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SystemRolePermission  $systemRolePermission
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemRolePermission $systemRolePermission)
    {
        if (Gate::denies('access',['system_role_permissions','delete'])){
            abort(403, 'Access Denied');
        }
        //
    }

    public function ajaxUpdateMultiple(Request $request)
    {

        $response ='';
        if($request->ajax())
        {
            $new_permissions = json_decode($request->new_permissions);
            
            foreach ($new_permissions as $new_permission){

                $id = $new_permission[0];
                $view = $new_permission[1];
                $add = $new_permission[2];
                $edit = $new_permission[3];
                $delete = $new_permission[4];

                $permission = SystemRolePermission::find($id);
                $permission->view = $view;
                $permission->add = $add;
                $permission->edit = $edit;
                $permission->delete = $delete;
                $permission->save();

            }
            $response = 'success';

        }

        echo json_encode($response);
    }


}
