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

Route::group(['prefix' => 'election_results', 'namespace' => 'App\Modules\Election_results\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'election_results.index', 'uses' => 'Election_results@index']);
    Route::get('/create', ['as' => 'election_results.create', 'uses' => 'Election_results@create']);
    Route::post('/store', ['as' => 'election_results.store', 'uses' => 'Election_results@store']);
    Route::get('/edit/{id}', ['as' => 'election_results.edit', 'uses' => 'Election_results@edit']);
    Route::post('/update/{id}', ['as' => 'election_results.update', 'uses' => 'Election_results@update']);
    Route::post('/delete/{id}', ['as' => 'election_results.delete', 'uses' => 'Election_results@delete']);
    Route::get('/getListAsPdf', ['as' => 'election_results.getListAsPdf', 'uses' => 'Election_results@getListAsPdf']);
    Route::get('/getListAsXls', ['as' => 'election_results.getListAsXls', 'uses' => 'Election_results@getListAsXls']);
});
