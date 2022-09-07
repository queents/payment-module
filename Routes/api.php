<?php

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


Route::middleware('auth:sanctum')->group(function () {
    Route::post("/payment", 'PaymentController@paymentMethod');
});
Route::get("/payment/returnUrl/{paymentMethod}", 'PaymentController@paymentReturnUrl');

Route::post("callback/payment/{method}", 'PaymentController@paymentCallback');
Route::get("callback/payment/{method}", 'PaymentController@paymentCallback');
Route::get("paytabs", function (){
    $pay=new \Modules\Payment\Http\Services\PayTabsPaymentService();
    return $pay->create();
});
