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

Route::group(['prefix' => 'reference_data', 'namespace' => 'App\Modules\Reference_data\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'reference_data.index', 'uses' => 'Reference_data@index']);
    Route::get('/create', ['as' => 'reference_data.create', 'uses' => 'Reference_data@create']);
    Route::post('/store', ['as' => 'reference_data.store', 'uses' => 'Reference_data@store']);
    Route::get('/edit/{id}', ['as' => 'reference_data.edit', 'uses' => 'Reference_data@edit']);
    Route::post('/update/{id}', ['as' => 'reference_data.update', 'uses' => 'Reference_data@update']);
    Route::post('/delete/{id}', ['as' => 'reference_data.delete', 'uses' => 'Reference_data@delete']);
    Route::get('/filterDistrict', ['as' => 'reference_data.filterDistrict', 'uses' => 'Reference_data@filterDistrict']);
    Route::get('/filterSubdistrict', ['as' => 'reference_data.filterSubdistrict', 'uses' => 'Reference_data@filterSubdistrict']);
    Route::get('/getListAsPdf', ['as' => 'reference_data.getListAsPdf', 'uses' => 'Reference_data@getListAsPdf']);
    Route::get('/getListAsXls', ['as' => 'reference_data.getListAsXls', 'uses' => 'Reference_data@getListAsXls']);
});
