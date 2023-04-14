<?php

use App\Http\Controllers\ActivitiesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/items','APIController@itemsIndex');
Route::get('/customers','APIController@customersIndex');
Route::get('/sales_invoice_headers','APIController@salesInvoiceHeadersList');
Route::get('/sales_invoice_lines','APIController@salesInvoiceLinesList');
Route::get('/value_entries','APIController@valueEntries');
Route::post('/project_activities', [ ActivitiesController::class, 'getProjectActivitiesApi'])->name('api.project.activities');