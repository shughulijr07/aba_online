<?php

namespace App\Models;

use App\Models\User\SystemUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'category',
        'role_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function staff(){
        return $this->hasOne(Staff::class);
    }

    public function system_role(){
        return $this->hasOne(SystemRole::class, 'id','role_id');
    }

    public function user_activities(){
        return $this->hasMany(UserActivity::class);
    }

    public function systemUser(){
        return $this->hasOne(SystemUser::class);
    }

    public static function authenticationFailedResponse(){
        return response()->json([
            'status' => 'fail',
            'message' => 'Please login first to perform this activity!'
        ]);
    }

    public static function userIsNotStaffResponse(){
        return response()->json([
            'status' => 'fail',
            'message' => 'Only '.CompanyInformation::$companyName.' Staff Are Allowed To Approve Requests'
        ]);
    }

    public static function userPermissionDeniedResponse(){
        return response()->json([
            'status' => 'fail',
            'message' => 'You have no permission to perform this activity in the System, please contact system Administrator for permission grant.'
        ]);
    }

    public static function staffIsNotAllowedToViewAnotherUsersItemResponse($itemName){
        return response()->json([
            'status' => 'fail',
            'message' => "Yo have no permission view Another Staff's ".$itemName
        ]);
    }

    public static function itemNotFoundResponse($itemName){
        return response()->json([
            'status' => 'fail',
            'message' => "Specified ".$itemName." Does Not Exist"
        ]);
    }

}
