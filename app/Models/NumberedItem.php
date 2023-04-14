<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberedItem extends Model
{
    protected $guarded = [];

    public function  number_series(){
        return $this->hasOne(NumberSeries::class);
    }
}
