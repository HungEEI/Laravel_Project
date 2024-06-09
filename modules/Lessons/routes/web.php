<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::prefix('lessons')->name('lessons.')->group(function () {
        Route::get('/{courseId}', "LessonController@index")->name('index');

        Route::get('/{courseId}/add', 'LessonController@add')->name('add');

        Route::get('/{courseId}/data', 'LessonController@data')->name('data');

        Route::post('/{courseId}/add', 'LessonController@store');

        Route::get('/edit/{lessonId}', 'LessonController@edit')->name('edit');
        Route::put('/edit/{lessonId}', 'LessonController@update')->name('update');

        // Route::delete('delete/{user}', 'LessonController@delete')->name('delete');
        
    });
});