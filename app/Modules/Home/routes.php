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

Route::group(['prefix' => 'home', 'namespace' => 'App\Modules\Home\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'home.index', 'uses' => 'Home@index']);
    Route::get('/getData', ['as' => 'home.getData', 'uses' => 'Home@getData']);
    Route::get('/getDataMonitoring', ['as' => 'home.getDataMonitoring', 'uses' => 'Home@getDataMonitoring']);
});
