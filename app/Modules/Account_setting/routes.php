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

Route::group(['prefix' => 'account_setting', 'namespace' => 'App\Modules\Account_setting\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'account_setting.index', 'uses' => 'Account_setting@index']);
    Route::post('/update/{id}', ['as' => 'account_setting.update', 'uses' => 'Account_setting@update']);
    Route::post('/update_password/{id}', ['as' => 'account_setting.update_password', 'uses' => 'Account_setting@update_password']);
    Route::post('/delete_img/{id}', ['as' => 'account_setting.delete_img', 'uses' => 'Account_setting@delete_img']);
});
