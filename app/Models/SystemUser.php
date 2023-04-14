<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SystemUser extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'phone_no',
        'email',
        'company',
        'description',
        'image',
        'passport',
        'thumbnail',
        'status',
        'user_id',
        'system_role_id',
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }


}
