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
            Route::post('change-password', ['AuthController@changePassword']);
        });

        Route::group(['prefix' => 'schedule'], function () {
            Route::get('/', 'ScheduleController@index');
            Route::post('create', 'ScheduleController@store');
            Route::post('view/{id}', 'ScheduleController@show');
            Route::post('update/{id}', 'ScheduleController@update');
            Route::delete('delete/{id}', 'ScheduleController@destroy');
        });

        Route::group(['prefix' => 'medical-reservation'], function () {
            Route::get('/', 'MedicalReservationController@index');
            Route::post('create', 'MedicalReservationController@store');
            Route::post('view/{id}', 'MedicalReservationController@show');
            Route::post('update/{id}', 'MedicalReservationController@update');
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

    });

});