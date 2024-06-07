<?php
use Illuminate\Support\Facades\Route;
use Modules\Lessons\src\Http\Controllers\LessonController;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('lessons')->name('lessons.')->group(function () {
        Route::get('/{courseId}', "LessonController@index")->name('index');

        Route::get('/{courseId}/add', 'LessonController@add')->name('add');

        Route::post('/{courseId}/add', 'LessonController@store');

        // Route::get('edit/{user}', 'LessonController@edit')->name('edit');
        // Route::put('edit/{user}', 'LessonController@update')->name('update');

        // Route::delete('delete/{user}', 'LessonController@delete')->name('delete');
        
    });
});