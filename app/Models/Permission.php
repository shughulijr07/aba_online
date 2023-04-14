<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public function system_role_permissions(){
       return $this->hasMany(SystemRolePermission::class);
    }
}
