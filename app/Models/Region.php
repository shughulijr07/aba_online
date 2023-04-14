<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $guarded = [];

    public function members(){
        return $this->hasMany(Member::class);
    }

    public function districts(){
        return $this->hasMany(District::class);
    }

    public function country(){
        return $this->belongsTo(Country::class );
    }
}
