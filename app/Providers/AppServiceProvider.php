<?php

namespace App\Providers;

use App\Models\AdvancePaymentRequest;
use App\Http\Controllers\LeavesController;
use App\Models\AdvancePaymentRequest as ModelsAdvancePaymentRequest;
use App\Models\Leave;
use App\Models\LeavePlan;
use App\Models\PerformanceObjective;
use App\Models\TimeSheet;
use App\Models\TravelRequest;
use Illuminate\Support\Facades\Schema as FacadesSchema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Schema;


class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        FacadesSchema::defaultStringLength(191);

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('leaveRequests', Leave::countLeaveRequests());
        });

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('timeSheets', TimeSheet::countTimeSheets());
        });

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('leavePlans', LeavePlan::countLeavePlans());
        });

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('travelRequests', TravelRequest::countTravelRequests());
        });

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('performanceObjectives', PerformanceObjective::countPerformanceObjectives());
        });

        view()->composer(['layouts.administrator.admin','layouts.administrator.admin-menu'], function ($view){
            $view->with('advancePaymentRequest', ModelsAdvancePaymentRequest::countRequests());
        });

    }
}
