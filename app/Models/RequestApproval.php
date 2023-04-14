<?php

namespace App\Models;

use App\Events\RequestActivityEvent;
use App\Events\SendToDynamicsNavEvent;
use Illuminate\Database\Eloquent\Model;

class RequestApproval extends Model
{
    protected $guarded = [];


    public static function approveSubmittedRequest($staff_request, $request_category, $request_name, $comments = ''): array
    {

        $current_user = auth()->user();
        $current_user_id = $current_user->id;
        $current_user_role = $current_user->role_id;
        $current_user_staff_id = $current_user->staff != null ? $current_user->staff->id : null;

        $staff_request_status = $staff_request->status;
        $staff_request_spv = $staff_request->responsible_spv;
        $request_no = $staff_request->no;

        $canRecordApproval = false;
        $new_request_status = null;
        $request_id = $staff_request->id;
        $approval_level = null;
        $approved_as = null;
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

            if($staff_request_spv == $current_user_staff_id ||  $current_user_role == 1){
                $new_request_status = 20; //'Waiting For ACC Approval'
                $approval_level = 'spv';
                $approved_as = "Supervisor";
                $next_level = 'acc';
                $notify_next_level = "yes";

                //approve only if the current user is the supervisor assigned to approve the request
                $canRecordApproval = true;
            }
            else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : You are not authorised to Approve this '.$request_name.' Request.';
            }

        }

        else if(in_array($current_user_role, [1,4]) && $staff_request_status == 20){ //for ACC
            $approval_level = 'acc';
            $approved_as = "Accountant";
            $new_request_status = 30;// Waiting For Finance Director Approval
            $next_level = 'fd';
            $notify_next_level = "yes";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,9]) && $staff_request_status == 30){ //for FD
            $approval_level = 'fd';
            $approved_as = "Finance Director";
            $new_request_status = 40;//
            $next_level = 'md';
            $notify_next_level = "yes";
            $canRecordApproval = true;
        }

        else if(in_array($current_user_role, [1,2]) && $staff_request_status == 40){ //for MD

            $approval_level = 'md';
            $approved_as = "Managing Director";
            $new_request_status = 50;// '50' => 'Approved',
            $next_level = null;
            $notify_next_level = "no";
            $canRecordApproval = true;
        }
        else{
            $feedbackStatus = "fail";
            $feedbackMessage = $request_no.' : You are not allowed to approve this '.$request_name.' Request.';
        }


        if($canRecordApproval){

            if($current_user_role == 1){
                $fakeTimeStamps = self::createFakeTimeStamps($staff_request);
                $created_at = $fakeTimeStamps['created_at'];
                $updated_at = $fakeTimeStamps['updated_at'];
            }

            //record staff_request approval
            $approval = new RequestApproval();
            $approval->request_id = $request_id;
            $approval->request_category = $request_category;
            $approval->level = $approval_level;
            $approval->done_by = $done_by;
            $approval->comments = $comments;
            $approval->next_level = $next_level;
            $approval->notify_next_level = $notify_next_level;
            $approval->created_at = $created_at;
            $approval->updated_at = $updated_at;
            $approval->save();

            if(isset($approval->id)){
                //update staff_request status
                $staff_request->status = $new_request_status;
                $staff_request->comments = $comments;
                $staff_request->updated_at = $updated_at;
                $staff_request->save();

                //send email|sms notifications
                $action_name = 'Approving';
                $action = $approval;
                event( new RequestActivityEvent($staff_request,$request_name,$request_category,$action_name,$action));

                if($new_request_status == 50){
                    //This means the request has been approved then send it to BC130
                    event( new SendToDynamicsNavEvent($staff_request,$request_category));
                }

                //record user activity
                $activity = [
                    'action'=> 'Approving',
                    'item'=> $request_category,
                    'item_id'=> $request_id,
                    'description'=> 'Approved '.$request_name.' with ID - '.$request_id.' as '.strtoupper($approved_as),
                    'user_id'=> auth()->user()->id,
                ];
                $activity_category = 'major';
                UserActivity::record_user_activity($activity, $activity_category);

                $feedbackStatus = "success";
                $feedbackMessage = $request_no.' : '.$request_name.' Request Approved successfully.';
            }else{
                $feedbackStatus = "fail";
                $feedbackMessage = $request_no.' : '.$request_name.' was not Approved due to Internal System Error';
            }
        }


        return [
            "status" => $feedbackStatus,
            "message" => $feedbackMessage,
        ];


    }

    public static function createFakeTimeStamps($staff_request){

        //Waiting For Supervisor,ACC,FD or MD Approval
        $lastUpdateDate = date("Y-m-d", strtotime($staff_request->updated_at));
        $lastUpdateTime = date("H:i:s", strtotime($staff_request->updated_at));
        $lastUpdateHour = explode(":", $lastUpdateTime)[0];
        $lastUpdateHour = $lastUpdateHour > 23 ? "00" : $lastUpdateHour;

        //Create Fake Time Stamps, Because request is being approved by Super Administrator
        $spvApprovalTime = Helper::generateRandomTime($lastUpdateTime, ($lastUpdateHour+1).":59:59");
        $spvUpdateHour = explode(":", $spvApprovalTime)[0];
        $spvUpdateHour = $spvUpdateHour > 23 ? "00" : $spvUpdateHour;

        $accApprovalTime = Helper::generateRandomTime($spvApprovalTime, ($spvUpdateHour+1).":59:59");
        $accUpdateHour = explode(":", $accApprovalTime)[0];
        $accUpdateHour = $accUpdateHour > 23 ? "00" : $accUpdateHour;

        $fdApprovalTime = Helper::generateRandomTime($accApprovalTime, ($accUpdateHour+1).":59:59");
        $fdUpdateHour = explode(":", $fdApprovalTime)[0];
        $fdUpdateHour = $fdUpdateHour > 23 ? "00" : $fdUpdateHour;

        $mdApprovalTime = Helper::generateRandomTime($fdApprovalTime, ($fdUpdateHour+1).":59:59");

        $spv_approval_date = $lastUpdateDate." ".$spvApprovalTime;
        $acc_approval_date = $lastUpdateDate." ".$accApprovalTime;
        $fd_approval_date = $lastUpdateDate." ".$fdApprovalTime;
        $md_approval_date = $lastUpdateDate." ".$mdApprovalTime;

        if($staff_request->status == 10){
            $created_at = $spv_approval_date;
            $updated_at = $spv_approval_date;
        }

        if($staff_request->status == 20){
            $created_at = $acc_approval_date;
            $updated_at = $acc_approval_date;
        }

        if($staff_request->status == 30){
            $created_at = $fd_approval_date;
            $updated_at = $fd_approval_date;
        }

        if($staff_request->status == 40){
            $created_at = $md_approval_date;
            $updated_at = $md_approval_date;
        }

        return [
            "created_at" => $created_at,
            "updated_at" => $updated_at,
        ];

    }

}
