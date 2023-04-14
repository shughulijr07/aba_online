<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo(){
        $role_id = Auth::user()->role_id;
        $user_status = Auth::user()->status;
        $staff = Auth::user()->staff;


        if( isset($staff->id) && $staff->staff_status == 'Active' && $user_status == 'active' ){

            switch ($role_id){ // check tha value or role_id in system_roles table
                case 1: return '/super-administrator'; break;
                case 2: return '/managing-director'; break;
                case 3: return '/human-resource-manager'; break;
                case 4: return '/accountant'; break;
                case 5: return '/supervisor'; break;
                case 6: return '/employee'; break;
                case 7: return '/system-administrator'; break;
                case 8: return '/system-administrator'; break;
                case 9: return '/finance-director'; break;
                default: return '/login'; break;
            }


        }else if($role_id ==  1){
            return '/super-administrator';
        }
        else{
            return '/login';
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
