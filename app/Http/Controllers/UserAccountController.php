<?php

namespace App\Http\Controllers;

use App\Events\ResetPasswordEvent;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends Controller
{
    public function changePassword(){

        if (Gate::denies('access',['user_account','store'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'user_account';
        $controller_name = 'user_account';
        $view_type = 'create';

        $user_role = auth()->user()->role_id;
        $extended_layout = 'layouts.administrator.admin';
        return view('user_account.change_password',
            compact( 'user_role', 'extended_layout', 'model_name', 'controller_name', 'view_type'));
    }

    public function updatePassword(){

    }

    public function resetPassword($staff_id)
    {
        if (Gate::denies('access',['user_account','edit'])){
            abort(403, 'Access Denied');
        }

        $staff = Staff::find($staff_id);
        $user = $staff->user;
        $default_password = 'password';
        $user->password = Hash::make($default_password);
        //dd( $default_password.' : '.Hash::make($default_password));
        $user->save();

        //send email
        event( new ResetPasswordEvent($staff));

        return redirect('staff/'.$staff->id)->with('message','Password Have Been Reset Successfully');

    }

    public function ajaxChangePassword(Request $request)
    {

        $response ='failed';

        if($request->ajax())
        {
            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $email = $request->email;

            $user = User::where('email', $email)->first();

            if($user != null){ //the email of user is existing in the users table

                $current_password = $user->password;

                if (Hash::check($old_password,$current_password)) {

                    $user->password = Hash::make($new_password);
                    $user->save();

                    $response = 'success';
                }
                else{
                    $response = 'wrong password';
                }

            }else {
                $response = 'wrong email address';
            }


        }

        echo json_encode($response);
    }

}
