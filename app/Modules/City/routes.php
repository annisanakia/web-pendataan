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

Route::group(['prefix' => 'city', 'namespace' => 'App\Modules\City\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'city.index', 'uses' => 'City@index']);
    Route::get('/create', ['as' => 'city.create', 'uses' => 'City@create']);
    Route::post('/store', ['as' => 'city.store', 'uses' => 'City@store']);
    Route::get('/edit/{id}', ['as' => 'city.edit', 'uses' => 'City@edit']);
    Route::post('/update/{id}', ['as' => 'city.update', 'uses' => 'City@update']);
    Route::post('/delete/{id}', ['as' => 'city.delete', 'uses' => 'City@delete']);
});
