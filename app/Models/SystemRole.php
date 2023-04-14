<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRole extends Model
{
    protected $guarded = [];

    public function users(){
        return $this->hasMany(User::class);
    }

    public function permissions(){
        return $this->hasMany(SystemRolePermission::class);
    }
    public static function get_system_roles_list(){
        $system_roles_list = [];
        $all_system_role = SystemRole::all();
        if ( auth()->user()->role_id == 1){
            $system_roles_list = $all_system_role;
        }else{
            foreach ($all_system_role as $system_role){
                $system_role_id = $system_role->id;
                if( $system_role_id != 1 && $system_role_id != 8 ){
                    $system_roles_list[] = $system_role;
                }
            }
        }
        return $system_roles_list;
    }
}
