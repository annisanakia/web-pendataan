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

Route::group(['prefix' => 'volunteer_data', 'namespace' => 'App\Modules\Volunteer_data\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'volunteer_data.index', 'uses' => 'Volunteer_data@index']);
    Route::get('/create', ['as' => 'volunteer_data.create', 'uses' => 'Volunteer_data@create']);
    Route::post('/store', ['as' => 'volunteer_data.store', 'uses' => 'Volunteer_data@store']);
    Route::get('/edit/{id}', ['as' => 'volunteer_data.edit', 'uses' => 'Volunteer_data@edit']);
    Route::post('/update/{id}', ['as' => 'volunteer_data.update', 'uses' => 'Volunteer_data@update']);
    Route::post('/delete/{id}', ['as' => 'volunteer_data.delete', 'uses' => 'Volunteer_data@delete']);
});
