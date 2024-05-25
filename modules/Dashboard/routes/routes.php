<?php 
use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\UserController;

Route::group(['namespace' => 'Modules\Dashboard\src\Http\Controllers'], function() {

    Route::prefix('admin')->group(function () {

        Route::get('/', 'DashboardController@index')->name('admin.dashboard');
            
    });

});