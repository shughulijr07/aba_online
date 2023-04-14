<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRolePermission extends Model
{
    protected $guarded = [];

    public function system_role(){
        return $this->belongsTo(SystemRole::class);
    }

    public function permission(){
        return $this->belongsTo(Permission::class);
    }
}
