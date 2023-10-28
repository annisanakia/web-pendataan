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

Route::group(['prefix' => 'report_data', 'namespace' => 'App\Modules\Report_data\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_data.index', 'uses' => 'Report_data@index']);
    Route::get('/getData', ['as' => 'report_data.getData', 'uses' => 'Report_data@getData']);
});
