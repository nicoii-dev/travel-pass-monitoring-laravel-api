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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('forgot-password', 'ForgotPasswordController@forgotPassword')->middleware('guest')->name('password.email');
        Route::post('reset-password', 'ForgotPasswordController@resetPassword')->middleware('guest')->name('password.reset');
        Route::post('verify-email', 'AuthController@verifyEmail');
    });
    
    //for authenticated
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('logout', 'AuthController@logout');
            Route::post('verify-token', 'AuthController@verifyToken');
            Route::post('change-password', 'AuthController@changePassword');
        });

        Route::group(['prefix' => 'schedule'], function () {
            Route::get('/', 'ScheduleController@index');
            Route::post('date', 'ScheduleController@getScheduleByDate');
            Route::post('medical', 'ScheduleController@medicalSchedules');
            Route::post('travelpass', 'ScheduleController@travelPassSchedules');
            Route::post('create', 'ScheduleController@store');
            Route::post('view/{id}', 'ScheduleController@show');
            Route::post('update/{id}', 'ScheduleController@update');
            Route::delete('delete/{id}', 'ScheduleController@destroy');
        });

        Route::group(['prefix' => 'medical-reservation'], function () {
            Route::get('/', 'MedicalReservationController@index');
            Route::get('verified', 'MedicalReservationController@showVerified');
            Route::post('view/user-sched', 'MedicalReservationController@getUserSchedule');
            Route::post('create', 'MedicalReservationController@store');
            Route::post('view/{id}', 'MedicalReservationController@show');
            Route::post('update/{id}', 'MedicalReservationController@update');
            Route::post('verify/{id}', 'MedicalReservationController@setUserToAppointed');
        });

        Route::group(['prefix' => 'medical-applications'], function () {
            Route::get('/', 'MedicalApplicationsController@index');
            Route::post('create', 'MedicalApplicationsController@store');
            Route::post('view/{id}', 'MedicalApplicationsController@show');
            Route::post('update', 'MedicalApplicationsController@update');

            Route::post('user-medical-applications', 'MedicalApplicationsController@getUserMedicalApplication');
        });

        Route::group(['prefix' => 'travelpass-reservation'], function () {
            Route::get('/', 'TravelPassReservationController@index');
            Route::post('view/user-sched', 'TravelPassReservationController@getUserSchedule');
            Route::post('create', 'TravelPassReservationController@store');
            Route::post('view/{id}', 'TravelPassReservationController@show');
            Route::post('update/{id}', 'TravelPassReservationController@update');
            Route::post('verify/{id}', 'TravelPassReservationController@setUserToAppointed');
        });

        Route::group(['prefix' => 'travelpass-applications'], function () {
            Route::get('/', 'TravelPassApplicationsController@index');
            Route::post('application', 'TravelPassApplicationsController@getUserApplication');
            Route::post('user', 'TravelPassApplicationsController@getUserQr');
            Route::post('create', 'TravelPassApplicationsController@store');
            Route::post('view/{id}', 'TravelPassApplicationsController@show');
            Route::post('approve/{id}', 'TravelPassApplicationsController@approve');
            Route::post('decline/{id}', 'TravelPassApplicationsController@decline');

            Route::post('user-travel-applications', 'TravelPassApplicationsController@getUserTravelApplication');
        });

        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', 'DashboardController@index');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index');
            Route::post('create', 'UserController@store');
            Route::post('view/{id}', 'UserController@show');
            Route::post('update/{id}', 'UserController@update');
            Route::post('activate/{id}', 'UserController@activateUser');
            Route::post('deactivate/{id}', 'UserController@deactivateUser');
        });

        Route::group(['prefix' => 'lsi'], function () {
            Route::get('/', 'LsiController@index');
            Route::post('view/{id}', 'LsiController@show');
        });

        Route::group(['prefix' => 'qrcode'], function () {
            Route::post('view', 'QrDetailsController@show');
        });

    });

});