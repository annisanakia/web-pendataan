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

Route::group(['prefix' => 'report_result', 'namespace' => 'App\Modules\Report_result\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'report_result.index', 'uses' => 'Report_result@index']);
    Route::get('/getData', ['as' => 'report_result.getData', 'uses' => 'Report_result@getData']);
    Route::get('/getListDistrictAsPdf', ['as' => 'report_result.getListDistrictAsPdf', 'uses' => 'Report_result@getListDistrictAsPdf']);
    Route::get('/getListDistrictAsXls', ['as' => 'report_result.getListDistrictAsXls', 'uses' => 'Report_result@getListDistrictAsXls']);
    Route::get('/getListSubdistrictAsPdf', ['as' => 'report_result.getListSubdistrictAsPdf', 'uses' => 'Report_result@getListSubdistrictAsPdf']);
    Route::get('/getListSubdistrictAsXls', ['as' => 'report_result.getListSubdistrictAsXls', 'uses' => 'Report_result@getListSubdistrictAsXls']);
    Route::get('/getListTPSAsPdf', ['as' => 'report_result.getListTPSAsPdf', 'uses' => 'Report_result@getListTPSAsPdf']);
    Route::get('/getListTPSAsXls', ['as' => 'report_result.getListTPSAsXls', 'uses' => 'Report_result@getListTPSAsXls']);
    Route::get('/quickCount', ['as' => 'report_result.quickCount', 'uses' => 'Report_result@quickCount']);
});
