<?php 
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', 'StudentController@index')->name('index');

        Route::get('/data', 'StudentController@data')->name('data');

        Route::get('/add', 'StudentController@add')->name('add');

        Route::post('/add', 'StudentController@store')->name('store');

        Route::get('edit/{student}', 'StudentController@edit')->name('edit');
        Route::put('edit/{student}', 'StudentController@update')->name('update');

        Route::delete('delete/{Student}', 'StudentController@delete')->name('delete');
    });
});