<?php

namespace App\Models;

use App\Events\RequestActivityEvent;
use Illuminate\Database\Eloquent\Model;

class RequestReturn extends Model
{

    public static function returnedByTitle($requestId,$requestCategory): string
    {
        //$returnLevel = $request->returns->last()->level;
        $requestReturn = RequestReturn::where("request_id", $requestId)
            ->where("request_category", $requestCategory)->orderBy("id", "DESC")->first();

        $returnLevel = $requestReturn != null ? $requestReturn->level : null;
        $returnedBy = "";

        if($returnLevel == 'spv') $returnedBy = "Supervisor";
        elseif($returnLevel == 'hrm') $returnedBy = "Human Resource Manager";
        elseif($returnLevel == 'acc') $returnedBy = "Accountant";
        elseif($returnLevel == 'fd')  $returnedBy = "Finance Director";
        elseif($returnLevel == 'md')  $returnedBy = "Managing Director";
        else $returnedBy = "";

        return $returnedBy;
    }



    public static function returnSubmittedRequest($staff_request, $comments, $request_category, $request_name): array
    {

        $current_user = auth()->user();
        $current_user_id = $current_user->id;
        $current_user_role = $current_user->role_id;
        $current_user_staff_id = $current_user->staff != null ? $current_user->staff->id : null;

        $staff_request_status = $staff_request->status;
        $staff_request_spv = $staff_request->responsible_spv;
        $request_no = $staff_request->no;

        $canRecordApproval = false;
        $new_request_status = 0;
        $request_id = $staff_request->id;
        $return_level = null;
        $returned_as = null;
        //$done_by = $current_user_id;
        $done_by = $current_user_staff_id;
        $next_level = null;
        $notify_next_level = "no";
        $created_at = date("Y-m-d H:i:s");
        $updated_at = date("Y-m-d H:i:s");
        $feedbackStatus = "fail";
        $feedbackMessage = "";

        if(in_array($staff_request->status , [50,99])){ //Approved || Rejected
            $feedbackStatus = "fail";
            $message = $staff_request->status == 99 ? 'Request Was Rejected.' : 'Request Has Already Been Approved.';
            $feedbackMessage = $request_no.' : '.$request_name.' '.$message ;
        }

        else if( (in_array($current_user_role, [1,2,3,4,5,9])) && $staff_request_status == 10 ){ //for SPV, HRM, ACC, FD and MD
            //Waiting For Supervisor Approval, accountant, hrm,ACC,FD and MD can be supervisors thus they can return travel requests
            // from staff they are supervising

            $return_level = 'spv';
            $returned_as = "Supervisor";

            if($staff_request_spv == $current_user_staff_id ||  $current_user_role == 1){
                //approve only if the current user is the supervisor assigned to approve the request
                $canRecordApproval = true;
            }
            else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : You are not authorised to Return this '.$request_name.' Request.';
            }

        }

        else if(in_array($current_user_role, [1,4]) && $staff_request_status == 20){ //for ACC
            $return_level = 'acc';
            $returned_as = "Accountant";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,9]) && $staff_request_status == 30){ //for FD
            $return_level = 'fd';
            $returned_as = "Finance Director";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,2]) && $staff_request_status == 40){ //for MD
            $return_level = 'md';
            $returned_as = "Managing Director";
            $canRecordApproval = true;
        }

        else{
            $feedbackStatus = "fail";
            $feedbackMessage = $request_no.' : You are not Authorized to Return this '.$request_name.' Request.';
        }


        if($canRecordApproval){

            if($current_user_role == 1){
                $fakeTimeStamps = RequestApproval::createFakeTimeStamps($staff_request);
                $created_at = $fakeTimeStamps['created_at'];
                $updated_at = $fakeTimeStamps['updated_at'];
            }

            //record staff_request return
            $return = new RequestReturn();
            $return->request_id = $request_id;
            $return->request_category = $request_category;
            $return->level = $return_level;
            $return->done_by = $done_by;
            $return->comments = $comments;
            $return->next_level = $next_level;
            $return->notify_next_level = $notify_next_level;
            $return->created_at = $created_at;
            $return->updated_at = $updated_at;
            $return->save();

            if(isset($return->id)){
                //update staff_request status
                $staff_request->status = $new_request_status;
                $staff_request->comments = $comments;
                $staff_request->updated_at = $updated_at;
                $staff_request->save();

                //send email|sms notifications
                $action_name = 'Returning';
                $action = $return;
                event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

                //record user activity
                $activity = [
                    'action'=> 'Returning',
                    'item'=> $request_category,
                    'item_id'=> $request_id,
                    'description'=> 'Returned '.$request_name.' Request with ID - '.$request_id.' as '.strtoupper($returned_as),
                    'user_id'=> auth()->user()->id,
                ];
                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

                $feedbackStatus = "success";
                $feedbackMessage = $request_no.' : '.$request_name.' Request Returned successfully.';
            }else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : '.$request_name.' was not Returned due to Internal System Error';
            }
        }


        return [
            "status" => $feedbackStatus,
            "message" => $feedbackMessage,
        ];


    }

}
