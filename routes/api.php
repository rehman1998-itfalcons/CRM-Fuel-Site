<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => ['guest:api']], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('signup', 'API\AuthController@signup');
});
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'API\AuthController@logout');
    Route::post('postdriver', 'API\AuthController@postDriver');
    Route::get('get-fuel-company', 'API\AuthController@getFuelCompany');
    Route::get('get-products', 'API\AuthController@recordProduct');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/invoice/{id}/details','InvoiceApiController@invoiceDetails')->name('invoice.details');
Route::get('/purchase/{id}/details','InvoiceApiController@PurchaseInvoiceDetail')->name('purchase.details');
