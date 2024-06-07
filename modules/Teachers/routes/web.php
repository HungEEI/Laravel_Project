<?php 
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', 'TeachersController@index')->name('index');

        Route::get('/data', 'TeachersController@data')->name('data');

        Route::get('/add', 'TeachersController@add')->name('add');

        Route::post('/add', 'TeachersController@store')->name('store');

        Route::get('edit/{teacher}', 'TeachersController@edit')->name('edit');
        Route::put('edit/{teacher}', 'TeachersController@update')->name('update');

        Route::delete('delete/{teacher}', 'TeachersController@delete')->name('delete');
    });
});
