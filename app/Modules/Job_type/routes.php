<?php
/*
    |--------------------------------------------------------------------------
    | Application Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register all of the routes for an application.
    | It's a breeze. Simply tell Laravel the URIs it should respond to
    | and give it the controller to call when that URI is requested.
    |
*/

Route::group(['prefix' => 'job_type', 'namespace' => 'App\Modules\Job_type\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'job_type.index', 'uses' => 'Job_type@index']);
    Route::get('/create', ['as' => 'job_type.create', 'uses' => 'Job_type@create']);
    Route::post('/store', ['as' => 'job_type.store', 'uses' => 'Job_type@store']);
    Route::get('/edit/{id}', ['as' => 'job_type.edit', 'uses' => 'Job_type@edit']);
    Route::post('/update/{id}', ['as' => 'job_type.update', 'uses' => 'Job_type@update']);
    Route::post('/delete/{id}', ['as' => 'job_type.delete', 'uses' => 'Job_type@delete']);
});
