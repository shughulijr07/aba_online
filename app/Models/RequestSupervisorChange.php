<?php

namespace App\Models;

use App\Events\RequestActivityEvent;
use Illuminate\Database\Eloquent\Model;

class RequestSupervisorChange extends Model
{
    public static function changeSupervisor($staff_request, $new_spv, $comments, $request_category, $request_name): array
    {

        $request_no = $staff_request->no;

        if(in_array($staff_request->status , [50,99])){ //Approved || Rejected
            $feedbackStatus = "fail";
            $message = $staff_request->status == 99 ? 'Request Was Rejected.' : 'Request Has Already Been Approved.';
            $feedbackMessage = $request_no.' : '.$request_name.' '.$message ;
        }else if($staff_request->status == 10){//Only Change SPV For Request Which is waiting SPV Approval

            $current_user = auth()->user();
            $current_user_role = $current_user->role_id;
            $current_user_staff_id = $current_user->staff != null ? $current_user->staff->id : null;
            $request_no = $staff_request->no;
            $request_id = $staff_request->id;
            $done_by = $current_user_staff_id;
            $old_spv = $staff_request->responsible_spv;
            $created_at = date("Y-m-d H:i:s");
            $updated_at = date("Y-m-d H:i:s");

            if($current_user_role == 1){
                $fakeTimeStamps = RequestApproval::createFakeTimeStamps($staff_request);
                $created_at = $fakeTimeStamps['created_at'];
                $updated_at = $fakeTimeStamps['updated_at'];
            }


            $spv_change = new RequestSupervisorChange();
            $spv_change->request_id = $request_id;
            $spv_change->request_category = $request_category;
            $spv_change->old_spv_id = $old_spv;
            $spv_change->new_spv_id = $new_spv;
            $spv_change->done_by = $done_by;
            $spv_change->comments = $comments;
            $spv_change->created_at = $created_at;
            $spv_change->updated_at = $updated_at;
            $spv_change->save();

            if(isset($spv_change->id)){
                //update staff_request status
                $staff_request->responsible_spv = $new_spv;
                $staff_request->comments = $comments;
                $staff_request->status = 10;
                $staff_request->updated_at = $updated_at;
                $staff_request->save();

                //send email|sms notifications
                $action_name = 'Changing Supervisor';
                $action = $spv_change;
                event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

                //record user activity
                $activity = [
                    'action'=> 'Changing Supervisor',
                    'item'=> $request_category,
                    'item_id'=> $request_id,
                    'description'=> 'Changed Supervisor for '.$request_name.' Request with ID - '.$request_id,
                    'user_id'=> auth()->user()->id,
                ];
                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

                $feedbackStatus = "success";
                $feedbackMessage = $request_no.' : Supervisor Have been Changed Successfully';
            }else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : Supervisor was not Changed due to Internal System Error';
            }
        }
        else{
            $feedbackStatus = "fail";
            $feedbackMessage = $request_no.' : You are not Allowed to Change Supervisor this '.$request_name.' Request.';
        }

        return [
            "status" => $feedbackStatus,
            "message" => $feedbackMessage,
        ];


    }

}
