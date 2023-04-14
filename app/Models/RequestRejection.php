<?php

namespace App\Models;

use App\Events\RequestActivityEvent;
use Illuminate\Database\Eloquent\Model;

class RequestRejection extends Model
{

    public static function rejectedByTitle($requestId,$requestCategory): string
    {
        $requestReject = RequestRejection::where("request_id", $requestId)
            ->where("request_category", $requestCategory)->orderBy("id", "DESC")->first();

        $rejectLevel = $requestReject != null ? $requestReject->level : null;

        $rejectedByTitle = "";

        if($rejectLevel == 'spv') $rejectedByTitle = "Supervisor";
        elseif($rejectLevel == 'hrm') $rejectedByTitle = "Human Resource Manager";
        elseif($rejectLevel == 'acc') $rejectedByTitle = "Accountant";
        elseif($rejectLevel == 'fd')  $rejectedByTitle = "Finance Director";
        elseif($rejectLevel == 'md')  $rejectedByTitle = "Managing Director";
        else $rejectedByTitle = "Administrator";


        return $rejectedByTitle;
    }



    public static function rejectSubmittedRequest($staff_request, $comments, $request_category, $request_name): array
    {

        $current_user = auth()->user();
        $current_user_id = $current_user->id;
        $current_user_role = $current_user->role_id;
        $current_user_staff_id = $current_user->staff != null ? $current_user->staff->id : null;

        $staff_request_status = $staff_request->status;
        $staff_request_spv = $staff_request->responsible_spv;
        $request_no = $staff_request->no;

        $canRecordApproval = false;
        $new_request_status = 99;
        $request_id = $staff_request->id;
        $rejection_level = null;
        $rejected_as = null;
        $done_by = $current_user_staff_id;
        $next_level = null;
        $notify_next_level = "no";
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $feedbackStatus = "fail";
        $feedbackMessage = "";


        if(in_array($staff_request->status , [50,99])){ //Approved || Rejected
            $feedbackStatus = "fail";
            $message = $staff_request->status == 99 ? 'Request Was Rejected Before.' : 'Request Has Already Been Approved.';
            $feedbackMessage = $request_no.' : '.$request_name.' '.$message ;
        }

        else if( (in_array($current_user_role, [1,2,3,4,5,9])) && $staff_request_status == 10 ){
            //for SPV, HRM, ACC, FD and MD
            //Waiting For Supervisor Approval, accountant, hrm,ACC,FD and MD can be supervisors thus they can reject requests
            // from staff they are supervising

            $rejection_level = 'spv';
            $rejected_as = "Supervisor";

            if($staff_request_spv == $current_user_staff_id ||  $current_user_role == 1){
                //reject only if the current user is the supervisor assigned to approve the request
                $canRecordApproval = true;
            }
            else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : You are not authorised to Reject this '.$request_name.' Request.';
            }

        }

        else if(in_array($current_user_role, [1,4]) && $staff_request_status == 20){ //for ACC
            $rejection_level = 'acc';
            $rejected_as = "Accountant";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,9]) && $staff_request_status == 30){ //for FD
            $rejection_level = 'fd';
            $rejected_as = "Finance Director";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,2]) && $staff_request_status == 40){ //for MD
            $rejection_level = 'md';
            $rejected_as = "Managing Director";
            $canRecordApproval = true;
        }else{
            $feedbackStatus = "fail";
            $feedbackMessage = $request_no.' : You are not Authorized to Reject this '.$request_name.' Request.';
        }


        if($canRecordApproval){

            if($current_user_role == 1){
                $fakeTimeStamps = self::createFakeTimeStamps($staff_request);
                $created_at = $fakeTimeStamps['created_at'];
                $updated_at = $fakeTimeStamps['updated_at'];
            }

            //record staff_request rejection
            $rejection = new RequestRejection();
            $rejection->request_id = $request_id;
            $rejection->request_category = $request_category;
            $rejection->level = $rejection_level;
            $rejection->done_by = $done_by;
            $rejection->comments = $comments;
            $rejection->next_level = $next_level;
            $rejection->notify_next_level = $notify_next_level;
            $rejection->created_at = $created_at;
            $rejection->updated_at = $updated_at;
            $rejection->save();

            if(isset($rejection->id)){
                //update staff_request status
                $staff_request->status = $new_request_status;
                $staff_request->comments = $comments;
                $staff_request->updated_at = $updated_at;
                $staff_request->save();

                //send email|sms notifications
                $action_name = 'Rejecting';
                $action = $rejection;
                event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

                //record user activity
                $activity = [
                    'action'=> 'Rejecting',
                    'item'=> $request_category,
                    'item_id'=> $request_id,
                    'description'=> 'Rejected '.$request_name.' with ID - '.$request_id.' as '.strtoupper($rejected_as),
                    'user_id'=> auth()->user()->id,
                ];
                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);


                $feedbackStatus = "success";
                $feedbackMessage = $request_no.' : '.$request_name.' Request Rejected successfully.';
            }else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : '.$request_name.' was not Rejected due to Internal System Error';
            }
        }


        return [
            "status" => $feedbackStatus,
            "message" => $feedbackMessage,
        ];


    }


}
