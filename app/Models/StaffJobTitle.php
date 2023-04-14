<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffJobTitle extends Model
{
    protected $fillable = [
        'title'
    ];

    public function staffs(){
        return $this->hasMany(Staff::class);
    }
}
