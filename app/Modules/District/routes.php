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

Route::group(['prefix' => 'district', 'namespace' => 'App\Modules\District\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'district.index', 'uses' => 'District@index']);
    Route::get('/create', ['as' => 'district.create', 'uses' => 'District@create']);
    Route::post('/store', ['as' => 'district.store', 'uses' => 'District@store']);
    Route::get('/edit/{id}', ['as' => 'district.edit', 'uses' => 'District@edit']);
    Route::post('/update/{id}', ['as' => 'district.update', 'uses' => 'District@update']);
    Route::post('/delete/{id}', ['as' => 'district.delete', 'uses' => 'District@delete']);
});
