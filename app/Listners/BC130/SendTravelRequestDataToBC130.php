<?php

namespace App\Listners\BC130;

use App\Models\BC130;
use App\Events\SendTravelRequestToBC130;
use App\Models\TravelRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTravelRequestDataToBC130
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  SendTravelRequestToBC130  $event
     * @return void
     */
    public function handle(SendTravelRequestToBC130 $event)
    {

        //get all time sheets which have not been sent to nav
        $travel_requests = TravelRequest::where('status','=','50')->where('transferred_to_nav','=','no')->get();


        if( count($travel_requests)>0){
            $bc130 = new BC130();
            foreach ($travel_requests as $travel_request) {
                $bc130->save_travel_request_to_bc130($travel_request);
            }
        }

        //dd('pause');
    }



}
