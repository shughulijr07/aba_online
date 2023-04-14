<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name'
    ];

    public function regions(){
        return $this->hasMany(Region::class);
    }

    public function members(){
        return $this->hasMany(Member::class);
    }
}
