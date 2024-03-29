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
    Route::get('/getListAsPdf', ['as' => 'report_data.getListAsPdf', 'uses' => 'Report_data@getListAsPdf']);
    Route::get('/getListAsXls', ['as' => 'report_data.getListAsXls', 'uses' => 'Report_data@getListAsXls']);
    Route::get('/getListDistrictAsPdf', ['as' => 'report_data.getListDistrictAsPdf', 'uses' => 'Report_data@getListDistrictAsPdf']);
    Route::get('/getListDistrictAsXls', ['as' => 'report_data.getListDistrictAsXls', 'uses' => 'Report_data@getListDistrictAsXls']);
    Route::get('/getListDistrictTPSAsPdf', ['as' => 'report_data.getListDistrictTPSAsPdf', 'uses' => 'Report_data@getListDistrictTPSAsPdf']);
    Route::get('/getListSubdistrictAsPdf', ['as' => 'report_data.getListSubdistrictAsPdf', 'uses' => 'Report_data@getListSubdistrictAsPdf']);
    Route::get('/getListSubdistrictAsXls', ['as' => 'report_data.getListSubdistrictAsXls', 'uses' => 'Report_data@getListSubdistrictAsXls']);
    Route::get('/getListCoordinatorAsPdf', ['as' => 'report_data.getListCoordinatorAsPdf', 'uses' => 'Report_data@getListCoordinatorAsPdf']);
    Route::get('/getListCoordinatorAsXls', ['as' => 'report_data.getListCoordinatorAsXls', 'uses' => 'Report_data@getListCoordinatorAsXls']);
    Route::get('/getListTPSAsPdf', ['as' => 'report_data.getListTPSAsPdf', 'uses' => 'Report_data@getListTPSAsPdf']);
    Route::get('/getListTPSAsXls', ['as' => 'report_data.getListTPSAsXls', 'uses' => 'Report_data@getListTPSAsXls']);
    Route::get('/getListGenderAsPdf', ['as' => 'report_data.getListGenderAsPdf', 'uses' => 'Report_data@getListGenderAsPdf']);
    Route::get('/getListGenderAsXls', ['as' => 'report_data.getListGenderAsXls', 'uses' => 'Report_data@getListGenderAsXls']);
    Route::get('/getListJobAsPdf', ['as' => 'report_data.getListJobAsPdf', 'uses' => 'Report_data@getListJobAsPdf']);
    Route::get('/getListJobAsXls', ['as' => 'report_data.getListJobAsXls', 'uses' => 'Report_data@getListJobAsXls']);
    Route::get('/getListAgeAsPdf', ['as' => 'report_data.getListAgeAsPdf', 'uses' => 'Report_data@getListAgeAsPdf']);
    Route::get('/getListAgeAsXls', ['as' => 'report_data.getListAgeAsXls', 'uses' => 'Report_data@getListAgeAsXls']);
    Route::get('/getListVolunteerAsPdf', ['as' => 'report_data.getListVolunteerAsPdf', 'uses' => 'Report_data@getListVolunteerAsPdf']);
    Route::get('/getListVolunteerAsXls', ['as' => 'report_data.getListVolunteerAsXls', 'uses' => 'Report_data@getListVolunteerAsXls']);
    Route::get('/getListSimpatisanAsPdf', ['as' => 'report_data.getListSimpatisanAsPdf', 'uses' => 'Report_data@getListSimpatisanAsPdf']);
    Route::get('/getListSimpatisanAsXls', ['as' => 'report_data.getListSimpatisanAsXls', 'uses' => 'Report_data@getListSimpatisanAsXls']);
    Route::get('/filterCoordinatorBySubdistrict', ['as' => 'report_data.filterCoordinatorBySubdistrict', 'uses' => 'Report_data@filterCoordinatorBySubdistrict']);
    Route::get('/getListSimpatisanCanvasserAsPdf', ['as' => 'report_data.getListSimpatisanCanvasserAsPdf', 'uses' => 'Report_data@getListSimpatisanCanvasserAsPdf']);
});
