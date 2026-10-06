<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('queue-work', function() {
//     Artisan::call('queue:work');
// });

Route::group(['middleware'=>'maintenance'],function(){
    Route::middleware(['prevent_back_history'])->group(function (){
        Route::middleware(['web'])->group(function () {
            
            // Route::get('invoice', ['uses' => 'Front\IndexController@downloadinvoice', 'as' => 'invoice']);

            // Route::get('', ['uses' => 'Front\IndexController@index', 'as' => 'index']);
            // Route::get('/', ['uses' => 'Front\IndexController@index', 'as' => '/']);
            // Route::get('index', ['uses' => 'Front\IndexController@index', 'as' => 'index']);
            Route::get('contact-us', ['uses' => 'Front\IndexController@contactUs', 'as' => 'contact_us']);
            Route::get('login', ['uses' => 'Front\IndexController@login', 'as' => 'login']);
            Route::get('register', ['uses' => 'Front\IndexController@register', 'as' => 'register']);
            Route::get('service', ['uses' => 'Front\IndexController@service', 'as' => 'service']);

            Route::post('sign-up', ['uses' => 'Front\IndexController@register', 'as' => 'sign_up']);
            
            Route::post('get-otp', ['uses' => 'Front\IndexController@getMobileOtp', 'as' => 'get_otp']);
            Route::post('get-login-otp', ['uses' => 'Front\IndexController@getLoginOtp', 'as' => 'get_login_otp']);
            
            Route::post('user-login', ['uses' => 'Front\IndexController@login', 'as' => 'user_login']);
            Route::post('otp-login', ['uses' => 'Front\IndexController@LoginWithOtp', 'as' => 'login_with_otp']);
            
            Route::post('send-forgot-password-link', ['uses' => 'Front\IndexController@sendForgotPasswordlink', 'as' => 'sendForgotPasswordlink']);
            Route::get('forgot-password/{token}', ['uses' => 'Front\IndexController@forgotPassword', 'as' => 'forgotpassword']);
            Route::post('reset-password', ['uses' => 'Front\IndexController@resetPassword', 'as' => 'reset_password']);
            Route::get('success', ['uses' => 'Front\IndexController@successPasswordUpdate', 'as' => 'successPasswordUpdate']);

            
        });
        
    });

//     Route::middleware(['user_logged_in'])->group(function () {
//         Route::get('logout', ['uses' => 'Front\IndexController@logout', 'as' => 'user_logout']);
//         Route::get('dashboard', ['uses' => 'Front\IndexController@dashboard', 'as' => 'user_dashboard']);
//         Route::get('application-form', ['uses' => 'Front\ApplicationController@applicationForm', 'as' => 'application_form']);
//         Route::post('user-application', ['uses' => 'Front\ApplicationController@applicationSubmit', 'as' => 'application_submit']);
//         Route::get('application/success', ['uses' => 'Front\ApplicationController@successPage', 'as' => 'success']);
        
//         Route::get('new-application/', ['uses' => 'Front\ApplicationController@newApplicationForm', 'as' => 'new_application']);
//         Route::get('community-complex-application/', ['uses' => 'Front\ApplicationController@communityComplexApplicationForm', 'as' => 'community_complex_application']);
//         Route::post('community-complex--application', ['uses' => 'Front\ApplicationController@communityComplexApplicationSubmit', 'as' => 'community_complex_application_submit']);
        
//     });
});

?>