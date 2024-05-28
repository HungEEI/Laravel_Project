<?php 
use Illuminate\Support\Facades\Route;
use Modules\Courses\src\Http\Controllers\CoursesController;

Route::group(['namespace' => 'Modules\Courses\src\Http\Controllers', 'middleware' => 'web'], function() {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', 'CoursesController@index')->name('index');

            Route::get('/data', 'CoursesController@data')->name('data');

            Route::get('/add', 'CoursesController@add')->name('add');

            Route::post('/add', 'CoursesController@store')->name('store');

            Route::get('edit/{course}', 'CoursesController@edit')->name('edit');
            Route::put('edit/{course}', 'CoursesController@update')->name('update');

            Route::delete('delete/{course}', 'CoursesController@delete')->name('delete');
        });
    });

});