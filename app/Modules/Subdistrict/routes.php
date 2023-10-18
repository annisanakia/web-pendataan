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

Route::group(['prefix' => 'subdistrict', 'namespace' => 'App\Modules\Subdistrict\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'subdistrict.index', 'uses' => 'Subdistrict@index']);
    Route::get('/create', ['as' => 'subdistrict.create', 'uses' => 'Subdistrict@create']);
    Route::post('/store', ['as' => 'subdistrict.store', 'uses' => 'Subdistrict@store']);
    Route::get('/edit/{id}', ['as' => 'subdistrict.edit', 'uses' => 'Subdistrict@edit']);
    Route::post('/update/{id}', ['as' => 'subdistrict.update', 'uses' => 'Subdistrict@update']);
    Route::post('/delete/{id}', ['as' => 'subdistrict.delete', 'uses' => 'Subdistrict@delete']);
});
