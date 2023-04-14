<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public static function record_user_activity($activity,$activity_category){

        //get system settings
        $system_settings = GeneralSetting::find(1);

        $user_activities_recording_mode = $system_settings->user_activities_recording_mode;
        //record user activity

        if( $user_activities_recording_mode !=3 ){ //3 - do not record any activity

            if( $user_activities_recording_mode == 1 ){ // 1 - record every  activity
                UserActivity::create([
                    'action'=> $activity['action'],
                    'item'=> $activity['item'],
                    'item_id'=> $activity['item_id'],
                    'description'=> $activity['description'],
                    'user_id'=> auth()->user()->id,
                ]);
            }

            if( $user_activities_recording_mode == 2 ){ // 2 - record major activities only

                if( $activity_category == 'major'){

                    UserActivity::create([
                        'action'=> $activity['action'],
                        'item'=> $activity['item'],
                        'item_id'=> $activity['item_id'],
                        'description'=> $activity['description'],
                        'user_id'=> auth()->user()->id,
                    ]);

                }
            }

        }

    }

}
