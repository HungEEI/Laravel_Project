<?php 
use Illuminate\Support\Facades\Route;
use Modules\User\src\Http\Controllers\UserController;

Route::group(['namespace' => 'Modules\User\src\Http\Controllers', 'middleware' => 'web'], function() {

    Route::prefix('admin')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', 'UserController@index')->name('admin.users.index');

            Route::get('/data', 'UserController@data')->name('admin.users.data');

            Route::get('/add', 'UserController@add')->name('admin.users.add');

            Route::post('/add', 'UserController@store')->name('admin.useres.store');

            Route::get('edit/{user}', 'UserController@edit')->name('admin.useres.edit');
            Route::post('edit/{user}', 'UserController@update')->name('admin.useres.update');
        });
    });

});