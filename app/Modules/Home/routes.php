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
    // Route::get('/recommendation_list', ['as' => 'home.recommendation_list', 'uses' => 'Home@recommendation_list']);
});
