<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::middleware(['prevent_back_history'])->group(function (){
    
    Route::get('/', ['uses' => 'Admin\AuthController@get_login', 'as' => 'admin-login']);

    Route::prefix('admin')->group(function() {

        Route::get('clear-cache', ['uses' => 'Controller@clear_cache', 'as' => 'clear-cache']);
        Route::get('layout-change', ['uses' => 'Admin\DashboardController@layout_change', 'as' => 'layout-change']);

        Route::middleware(['admin_not_logged_in'])->group(function () {
            Route::get('/', ['uses' => 'Admin\AuthController@get_login', 'as' => 'admin-login']);
            Route::get('login', ['uses' => 'Admin\AuthController@get_login', 'as' => 'admin-login']);
            Route::post('login', ['uses' => 'Admin\AuthController@post_login', 'as' => 'admin-loginn']);
            
        });

        Route::middleware(['admin_logged_in','permission','manufacture_login','warehouse_login'])->group(function () {

            Route::get('logout', ['uses' => 'Admin\AuthController@logout', 'as' => 'admin-logout']);
            Route::get('dashboard', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin-dashboard']);

            Route::get('search-state', ['uses' => 'Admin\DashboardController@search_state', 'as' => 'search-state']);
            Route::get('search-city', ['uses' => 'Admin\DashboardController@search_city', 'as' => 'search-city']);

            Route::get('admin-profile', ['uses' => 'Admin\DashboardController@get_profile', 'as' => 'admin-profile']);
            Route::post('admin-profile', ['uses' => 'Admin\DashboardController@post_profile', 'as' => 'admin-profile']);
            Route::post('admin-change-password', ['uses' => 'Admin\DashboardController@post_change_password', 'as' => 'admin-change-password']);


            Route::get('settings', ['uses' => 'Admin\SettingsController@index', 'as' => 'settings','label' => 'Get Settings']);
            Route::post('settings', ['uses' => 'Admin\SettingsController@store', 'as' => 'settings','label' => 'Post Settings']);
        

            Route::get('customer-list', ['uses' => 'Admin\CustomerController@index', 'as' => 'customer-list','label' => 'customer List']);
            Route::get('customer-datatable', ['uses' => 'Admin\CustomerController@getStudentDatatable', 'as' => 'customer-datatable']);
            Route::get('customer-status-change/{id}', ['uses' => 'Admin\CustomerController@statusChange', 'as' => 'customer-status-change']);
            Route::get('customer-add', ['uses' => 'Admin\CustomerController@create', 'as' => 'customer-add']);
            Route::post('customer-store', ['uses' => 'Admin\CustomerController@store', 'as' => 'customer-store']);
            Route::get('customer-delete/{id}', ['uses' => 'Admin\CustomerController@delete', 'as' => 'customer-delete']);
            Route::get('customer-edit/{id}', ['uses' => 'Admin\CustomerController@edit', 'as' => 'customer-edit',]);
            Route::post('customer-update', ['uses' => 'Admin\CustomerController@update', 'as' => 'customer-update']);
            // Route::get('assigned-road-list/{id}', ['uses' => 'Admin\CustomerController@assignRoadList', 'as' => 'assigned-road-list',]);
            
            Route::get('areas', ['uses' => 'Admin\AreaManagementController@index', 'as' => 'areas']);
            Route::get('get-area-datatable', ['uses' => 'Admin\AreaManagementController@getAreaDatatable', 'as' => 'area-datatable']);
            Route::get('area-add', ['uses' => 'Admin\AreaManagementController@areaAdd', 'as' => 'area-add']);
            Route::post('area-store', ['uses' => 'Admin\AreaManagementController@areaStore', 'as' => 'area-store']);
            Route::get('area-delete/{id}', ['uses' => 'Admin\AreaManagementController@areaDelete', 'as' => 'area-delete']);
            Route::get('area-status-change/{id}', ['uses' => 'Admin\AreaManagementController@areaStatusChange', 'as' => 'area-status-change']);

            Route::get('roads', ['uses' => 'Admin\RoadManagementController@index', 'as' => 'roads']);
            Route::get('get-road-datatable', ['uses' => 'Admin\RoadManagementController@getRoadDatatable', 'as' => 'road-datatable']);
            Route::get('road-add', ['uses' => 'Admin\RoadManagementController@create', 'as' => 'road-add']);
            Route::post('road-store', ['uses' => 'Admin\RoadManagementController@store', 'as' => 'road-store']);
            Route::get('road-edit/{id}', ['uses' => 'Admin\RoadManagementController@edit', 'as' => 'road-edit']);
            Route::post('road-update', ['uses' => 'Admin\RoadManagementController@update', 'as' => 'road-update']);
            Route::get('road-delete/{id}', ['uses' => 'Admin\RoadManagementController@delete', 'as' => 'road-delete']);
            Route::get('road-status-change/{id}', ['uses' => 'Admin\RoadManagementController@statusChange', 'as' => 'road-status-change']);
            
            Route::get('vehicles', ['uses' => 'Admin\VehicleManagement@index', 'as' => 'vehicles']);
            Route::get('get-vehicle-datatable', ['uses' => 'Admin\VehicleManagement@getVehicleDatatable', 'as' => 'vehicle-datatable']);
            Route::get('vehicle-add', ['uses' => 'Admin\VehicleManagement@create', 'as' => 'vehicle-add']);
            Route::post('vehicle-store', ['uses' => 'Admin\VehicleManagement@store', 'as' => 'vehicle-store']);
            Route::get('vehicle-edit/{id}', ['uses' => 'Admin\VehicleManagement@edit', 'as' => 'vehicle-edit']);
            Route::post('vehicle-update', ['uses' => 'Admin\VehicleManagement@update', 'as' => 'vehicle-update']);
            Route::get('vehicle-delete/{id}', ['uses' => 'Admin\VehicleManagement@delete', 'as' => 'vehicle-delete']);
            Route::get('vehicle-status-change/{id}', ['uses' => 'Admin\VehicleManagement@statusChange', 'as' => 'vehicle-status-change']);
            
            
            Route::get('staff-create', ['uses' => 'Admin\StaffController@create', 'as' => 'user-create']);
            Route::post('staff-store', ['uses' => 'Admin\StaffController@store', 'as' => 'user-store']);
            Route::get('staff-edit/{id}', ['uses' => 'Admin\StaffController@edit', 'as' => 'staff-edit']);
            Route::post('staff-update', ['uses' => 'Admin\StaffController@update', 'as' => 'staff-update']);
            Route::get('staff-delete/{id}', ['uses' => 'Admin\StaffController@delete', 'as' => 'staff-delete']);
            Route::get('staff-status-change/{id}', ['uses' => 'Admin\StaffController@statusChange', 'as' => 'staff-status-change']);
            
            Route::get('drivers', ['uses' => 'Admin\StaffController@driverList', 'as' => 'drivers']);
            Route::get('get-driver-datatable', ['uses' => 'Admin\StaffController@getDriverDatatable', 'as' => 'driver-datatable']);
            Route::get('driver-assign-location/{id}', ['uses' => 'Admin\StaffController@driverAssignLoaction', 'as' => 'driver-assign-location']);
            Route::post('driver-area-update', ['uses' => 'Admin\StaffController@driverAreaUpdate', 'as' => 'driver-area-update']);
            Route::post('driver-road-arrangement', ['uses' => 'Admin\StaffController@driverRoadArrangement', 'as' => 'driver-road-arrangement']);
            Route::get('driver-warehouse/{id}', ['uses' => 'Admin\StaffController@driverWarehouse', 'as' => 'driver-warehouse']);
            Route::post('driver-warehouse-update', ['uses' => 'Admin\StaffController@updateDriverWarehouse', 'as' => 'driver-warehouse-update']);
            
            Route::get('sales-executives', ['uses' => 'Admin\SalesExecutiveController@salesExecutives', 'as' => 'sales-executives']);
            Route::get('get-sales-executive-datatable', ['uses' => 'Admin\SalesExecutiveController@getSalesExecutiveDatatable', 'as' => 'sales-executive-datatable']);
            Route::get('sales-executive-assign-location/{id}', ['uses' => 'Admin\SalesExecutiveController@salesExecutiveAssignLoaction', 'as' => 'sales-executive-assign-location']);
            Route::post('sales-executive-area-update', ['uses' => 'Admin\SalesExecutiveController@salesExecutiveAreaUpdate', 'as' => 'sales-executive-area-update']);
            Route::get('sales-executive-area-log/{id}', ['uses' => 'Admin\SalesExecutiveController@salesExecutiveAreaLog', 'as' => 'sales-executive-area-log']);
            Route::get('sales-executive-attendance', ['uses' => 'Admin\SalesExecutiveController@salesExecutiveAttendance', 'as' => 'sales-executive-attendance']);
            Route::get('get-sales-executive-attendance-datatable', ['uses' => 'Admin\SalesExecutiveController@getSalesExecutiveAttendanceDatatable', 'as' => 'sales-executive-attendance-datatable']);
            Route::get('sales-executive-area-logs-list', ['uses' => 'Admin\SalesExecutiveController@salesExecutiveAreaLogsList', 'as' => 'sales-executive-area-logs-list']);
            Route::get('get-sales-executive-area-logs-list-datatable', ['uses' => 'Admin\SalesExecutiveController@getSalesExecutiveAreaLogsListDatatable', 'as' => 'sales-executive-area-logs-list-datatable']);
            
            Route::get('warehouses', ['uses' => 'Admin\WarehouseController@warehouseList', 'as' => 'warehouses']);
            Route::get('get-warehouse-datatable', ['uses' => 'Admin\WarehouseController@getWarehouseDatatable', 'as' => 'warehouse-datatable']);
           
            Route::get('products', ['uses' => 'Admin\ProductController@index', 'as' => 'products']);
            Route::get('get-product-datatable', ['uses' => 'Admin\ProductController@getProductDatatable', 'as' => 'product-datatable']);
            Route::get('product-create', ['uses' => 'Admin\ProductController@create', 'as' => 'product-create']);
            Route::post('product-store', ['uses' => 'Admin\ProductController@store', 'as' => 'product-store']);
            Route::get('product-edit/{id}', ['uses' => 'Admin\ProductController@edit', 'as' => 'product-edit']);
            Route::post('product-update', ['uses' => 'Admin\ProductController@update', 'as' => 'product-update']);
            Route::get('product-delete/{id}', ['uses' => 'Admin\ProductController@delete', 'as' => 'product-delete']);
            Route::get('product-status-change/{id}', ['uses' => 'Admin\ProductController@statusChange', 'as' => 'product-status-change']); 
            
            Route::get('invoice', ['uses' => 'Admin\ProductController@downloadinvoice', 'as' => 'invoice']);
            
            Route::get('invoice-list', ['uses' => 'Admin\ProductController@invoiceList', 'as' => 'invoice-list']);
            Route::get('get-invoice-datatable', ['uses' => 'Admin\ProductController@getInvoiceDatatable', 'as' => 'invoice-datatable']);
            
            Route::get('driver-attendance', ['uses' => 'Admin\StaffController@driverAttendance', 'as' => 'driver-attendance']);
            Route::get('get-driver-attendance-datatable', ['uses' => 'Admin\StaffController@getDriverAttendanceDatatable', 'as' => 'driver-attendance-datatable']);

            Route::get('user-monthly-attendance/{id}', ['uses' => 'Admin\StaffController@driverMonthlyAttendance', 'as' => 'driver-monthly-attendance']);
            Route::get('driver-monthly-route-log/{id}', ['uses' => 'Admin\StaffController@driverMonthlyRouteLog', 'as' => 'driver-monthly-route-log']);

            Route::get('driver-route-logs', ['uses' => 'Admin\StaffController@driverRouteLogs', 'as' => 'driver-route-logs']);
            Route::get('get-driver-route-logs-datatable', ['uses' => 'Admin\StaffController@getDriverRouteLogsDatatable', 'as' => 'driver-route-logs-datatable']);

            
        });
 
    });

    Route::prefix('manufacture')->group(function() {

        Route::get('clear-cache', ['uses' => 'Controller@clear_cache', 'as' => 'manufacture-clear-cache']);
        Route::get('layout-change', ['uses' => 'Admin\DashboardController@layout_change', 'as' => 'layout-change']);

        Route::middleware(['admin_not_logged_in'])->group(function () {
            Route::get('/', ['uses' => 'Manufacture\AuthController@get_login', 'as' => 'manufacture-login']);
            Route::get('login', ['uses' => 'Manufacture\AuthController@get_login', 'as' => 'manufacture-login']);
            Route::post('login', ['uses' => 'Manufacture\AuthController@post_login', 'as' => 'manufacture-loginn']);
            
        });

        Route::middleware(['admin_logged_in','permission','warehouse_login'])->group(function () {

            Route::get('logout', ['uses' => 'Manufacture\AuthController@logout', 'as' => 'manufacture-logout']);
            Route::get('dashboard', ['uses' => 'Manufacture\DashboardController@index', 'as' => 'manufacture-dashboard']);

            Route::get('manufacture-profile', ['uses' => 'Manufacture\DashboardController@get_profile', 'as' => 'manufacture-profile']);
            Route::post('manufacture-profile', ['uses' => 'Manufacture\DashboardController@post_profile', 'as' => 'manufacture-profile']);
            Route::post('manufacture-change-password', ['uses' => 'Manufacture\DashboardController@post_change_password', 'as' => 'manufacture-change-password']);
            Route::get('settings', ['uses' => 'Manufacture\SettingsController@index', 'as' => 'manufacture-settings','label' => 'Get Settings']);
            Route::post('settings', ['uses' => 'Manufacture\SettingsController@store', 'as' => 'manufacture-settings','label' => 'Post Settings']);
        
            Route::get('products', ['uses' => 'Manufacture\ProductController@index', 'as' => 'manufacture-products']);
            Route::get('get-product-datatable', ['uses' => 'Manufacture\ProductController@getProductDatatable', 'as' => 'manufacture-product-datatable']);
            Route::get('product-create', ['uses' => 'Manufacture\ProductController@create', 'as' => 'manufacture-product-create']);
            Route::post('product-store', ['uses' => 'Manufacture\ProductController@store', 'as' => 'manufacture-product-store']);
            Route::get('product-edit/{id}', ['uses' => 'Manufacture\ProductController@edit', 'as' => 'manufacture-product-edit']);
            Route::post('product-update', ['uses' => 'Manufacture\ProductController@update', 'as' => 'manufacture-product-update']);
            Route::get('product-delete/{id}', ['uses' => 'Manufacture\ProductController@delete', 'as' => 'manufacture-product-delete']);
            
        });
 
    });
    
    Route::prefix('warehouse')->group(function() {

        Route::get('clear-cache', ['uses' => 'Controller@clear_cache', 'as' => 'warehouse-clear-cache']);
        Route::get('layout-change', ['uses' => 'Admin\DashboardController@layout_change', 'as' => 'layout-change']);

        Route::middleware(['admin_not_logged_in'])->group(function () {
            Route::get('/', ['uses' => 'Warehouse\AuthController@get_login', 'as' => 'warehouse-login']);
            Route::get('login', ['uses' => 'Warehouse\AuthController@get_login', 'as' => 'warehouse-login']);
            Route::post('login', ['uses' => 'Warehouse\AuthController@post_login', 'as' => 'warehouse-loginn']);
            
        });

        Route::middleware(['admin_logged_in','permission'])->group(function () {

            Route::get('logout', ['uses' => 'Warehouse\AuthController@logout', 'as' => 'warehouse-logout']);
            Route::get('dashboard', ['uses' => 'Warehouse\DashboardController@index', 'as' => 'warehouse-dashboard']);

            Route::get('warehouse-profile', ['uses' => 'Warehouse\DashboardController@get_profile', 'as' => 'warehouse-profile']);
            Route::post('warehouse-profile', ['uses' => 'Warehouse\DashboardController@post_profile', 'as' => 'warehouse-profile']);
            Route::post('warehouse-change-password', ['uses' => 'Warehouse\DashboardController@post_change_password', 'as' => 'warehouse-change-password']);
            Route::get('settings', ['uses' => 'Warehouse\SettingsController@index', 'as' => 'warehouse-settings','label' => 'Get Settings']);
            Route::post('settings', ['uses' => 'Warehouse\SettingsController@store', 'as' => 'warehouse-settings','label' => 'Post Settings']);
        
            Route::get('products', ['uses' => 'Warehouse\ProductController@index', 'as' => 'warehouse-products']);
            Route::get('get-product-datatable', ['uses' => 'Warehouse\ProductController@getProductDatatable', 'as' => 'warehouse-product-datatable']);
            Route::get('product-details/{id}', ['uses' => 'Warehouse\ProductController@edit', 'as' => 'warehouse-product-details']);
            Route::post('product-update', ['uses' => 'Warehouse\ProductController@update', 'as' => 'warehouse-product-update']);
            
            
            Route::get('stock', ['uses' => 'Warehouse\StockController@index', 'as' => 'warehouse-stock']);
            Route::get('get-stock-datatable', ['uses' => 'Warehouse\StockController@getStockDatatable', 'as' => 'warehouse-stock-datatable']);
            
            Route::get('invoice', ['uses' => 'Warehouse\StockController@invoice', 'as' => 'warehouse-invoice']);
            Route::get('get-invoice-datatable', ['uses' => 'Warehouse\StockController@getInvoiceDatatable', 'as' => 'warehouse-invoice-datatable']);
            Route::get('invoice-create', ['uses' => 'Warehouse\StockController@invoiceCreate', 'as' => 'warehouse-invoice-create']);
            Route::post('invoice-store', ['uses' => 'Warehouse\StockController@invoiceStore', 'as' => 'warehouse-invoice-store']);
            
            Route::get('driver-product-create', ['uses' => 'Warehouse\DriverManagementController@productAdd', 'as' => 'driver-product-create']);
            Route::post('driver-product-store', ['uses' => 'Warehouse\DriverManagementController@productStore', 'as' => 'driver-product-store']);
            Route::get('driver-management-report', ['uses' => 'Warehouse\DriverManagementController@driverManagementReport', 'as' => 'driver-management-report']);
            Route::get('get-driver-management-report-datatable', ['uses' => 'Warehouse\DriverManagementController@getDriverManagementReportDatatable', 'as' => 'get-driver-management-report-datatable']);
             
            Route::get('driver-product-list/{id}', ['uses' => 'Warehouse\DriverManagementController@driverProductList', 'as' => 'driver-product-list']);

            
            
        });
 
    });
    
  
    
    
   
    


});