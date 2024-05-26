<?php 
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Modules\Categories\src\Http\Controllers', 'middleware' => 'web'], function() {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', 'CategoriesController@index')->name('index');

            Route::get('/data', 'CategoriesController@data')->name('data');

            Route::get('/add', 'CategoriesController@add')->name('add');

            Route::post('/add', 'CategoriesController@store')->name('store');

            Route::get('edit/{Category}', 'CategoriesController@edit')->name('edit');
            Route::put('edit/{Category}', 'CategoriesController@update')->name('update');

            Route::delete('delete/{Category}', 'CategoriesController@delete')->name('delete');
        });
    });

});