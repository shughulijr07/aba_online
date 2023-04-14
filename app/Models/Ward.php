<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = [
        'district_id',
        'name'
    ];

    public function members(){
        return $this->hasMany(Member::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }
}
