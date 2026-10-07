<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::post('login', ['uses' => 'Api\AuthController@login', 'as' => 'login']);
    Route::post('forgot-password', ['uses' => 'Api\AuthController@forgotPassword', 'as' => 'forgotPassword']);
    Route::post('logout', ['uses' => 'Api\AuthController@logout', 'as' => 'logout']);

Route::prefix('sales-executive')->group(function() {

   
});

Route::prefix('driver')->group(function() {
    Route::post('punch-in', ['uses' => 'Api\DriverApiController@driverPunchIn', 'as' => 'punch-in']);
    Route::post('punch-out', ['uses' => 'Api\DriverApiController@driverPunchOut', 'as' => 'punch-out']);
    Route::post('break', ['uses' => 'Api\DriverApiController@driverBreak', 'as' => 'break']);
    
    Route::post('routes', ['uses' => 'Api\DriverApiController@driverRoute', 'as' => 'routes']);
    Route::post('route-details', ['uses' => 'Api\DriverApiController@routeDetails', 'as' => 'route-details']);
    Route::post('route-shops', ['uses' => 'Api\DriverApiController@routeShops', 'as' => 'route-shops']);
    Route::post('add-customer', ['uses' => 'Api\DriverApiController@addCustomer', 'as' => 'add-customer']);
    Route::post('customer-details', ['uses' => 'Api\DriverApiController@customerDetails', 'as' => 'customer-details']);
    Route::post('customer-update', ['uses' => 'Api\DriverApiController@customerUpdate', 'as' => 'customer-update']);

    Route::post('update-delivary-status', ['uses' => 'Api\DriverApiController@updateDelivaryStatus', 'as' => 'update-delivary-status']);
    
    Route::post('vehicle-details', ['uses' => 'Api\DriverApiController@vehicleDetails', 'as' => 'vehicle-details']);
    Route::post('profile', ['uses' => 'Api\DriverApiController@profile', 'as' => 'profile']);
    Route::post('profile-update', ['uses' => 'Api\DriverApiController@UpdateProfile', 'as' => 'profile-update']);
    Route::post('driver-completed-deliveries', ['uses' => 'Api\DriverApiController@driverCompletedDeliveries', 'as' => 'driver-completed-deliveries']);
    
    Route::post('invoice-data', ['uses' => 'Api\ProductApiController@getInvoiceData', 'as' => 'invoice-data']);
    Route::post('get-invoice', ['uses' => 'Api\ProductApiController@generatePdf', 'as' => 'get-invoice']);
    
    Route::post('shop-sequence', ['uses' => 'Api\DriverApiController@shopSequence', 'as' => 'shop-sequence']);
    Route::post('invoice-list', ['uses' => 'Api\ProductApiController@invoiceList', 'as' => 'invoice-list']);
    Route::post('shop-filter', ['uses' => 'Api\DriverApiController@routeShopsFilter', 'as' => 'shop-filter']);
    
    Route::post('driver-attendance', ['uses' => 'Api\DriverApiController@driverAttendance', 'as' => 'driver-attendance']);
});

Route::get('product-list', ['uses' => 'Api\ProductApiController@productList', 'as' => 'product-list']);

Route::prefix('sales-executive')->group(function() {
    Route::post('routes', ['uses' => 'Api\SalesExecutiveController@routeList', 'as' => 'route-list']);
    Route::post('add-shop', ['uses' => 'Api\SalesExecutiveController@addShop', 'as' => 'add-shop']);
    Route::post('shop-list', ['uses' => 'Api\SalesExecutiveController@shopList', 'as' => 'shop-list']);
    Route::post('shop-details', ['uses' => 'Api\SalesExecutiveController@getSingleShop', 'as' => 'shop-details']);
    Route::post('shop-visit', ['uses' => 'Api\SalesExecutiveController@shopVisit', 'as' => 'shop-visit']);
    
    Route::post('punch-in', ['uses' => 'Api\SalesExecutiveController@salesExecutivePunchIn', 'as' => 'sales-executive-punch-in']);
    Route::post('punch-out', ['uses' => 'Api\SalesExecutiveController@salesExecutivePunchOut', 'as' => 'sales-executive-punch-out']);
    Route::post('break', ['uses' => 'Api\SalesExecutiveController@salesExecutiveBreak', 'as' => 'sales-executive-break']);
    
    Route::post('area-list', ['uses' => 'Api\SalesExecutiveController@salesExecutiveArea', 'as' => 'sales-executive-area-list']);
    
});
