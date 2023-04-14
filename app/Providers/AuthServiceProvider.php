<?php

namespace App\Providers;

use App\Models\SystemRolePermission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*************************** gates for menu items **********************/
        Gate::define('view-menu', function($user,$permission_name){
            $system_role_id = $user->role_id;
            $permission_name = $permission_name;
            $permissions = $this->getPermissions($system_role_id, $permission_name);

            // return $permissions->view == 'true' ? true : false; // real
            return true;
        });


        /*************************** gates for actions **********************/
        Gate::define('view-action', function($user,$page_name,$action){

            $permission_name = $page_name;
            $system_role_id = $user->role_id;

            $permissions = $this->getPermissions($system_role_id, $permission_name);

            $granted = '';
            switch($action){
                case 'view' : $granted = $permissions->view; break;
                case 'add' : $granted = $permissions->add; break;
                case 'edit' : $granted = $permissions->edit; break;
                case 'delete' : $granted = $permissions->delete; break;
                default : $granted = 'false'; break;

            }

            return $granted == 'true' ? true : false;
        });


        /*************************** gates for classes **********************/
        Gate::define('access', function($user,$permission_name,$action){

            $system_role_id = $user->role_id;

            $permissions = $this->getPermissions($system_role_id, $permission_name);
     
            $granted = '';
            switch($action){
                case 'view' : $granted = $permissions->view; break;
                case 'store' : $granted = $permissions->add; break;
                case 'edit' : $granted = $permissions->edit; break;
                case 'delete' : $granted = $permissions->delete; break;
                default : $granted = 'false'; break;

            }

            return $granted == 'true' ? true : false;
        });




    }

    public function getPermissions($system_role_id,$permission_name){

        $permissions = DB::table('system_role_permissions')
            ->join('permissions','system_role_permissions.permission_id','=','permissions.id')
            ->select('system_role_permissions.*')
            ->where('system_role_permissions.system_role_id','=',$system_role_id)
            ->where('permissions.permission_name','=',$permission_name)
            ->get()->first();

        return $permissions;

    }
}
