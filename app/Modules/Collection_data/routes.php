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

Route::group(['prefix' => 'collection_data', 'namespace' => 'App\Modules\Collection_data\Controllers', 'middleware' => ['web']], function () {
    Route::get('/', ['as' => 'collection_data.index', 'uses' => 'Collection_data@index']);
    Route::get('/create', ['as' => 'collection_data.create', 'uses' => 'Collection_data@create']);
    Route::post('/store', ['as' => 'collection_data.store', 'uses' => 'Collection_data@store']);
    Route::get('/edit/{id}', ['as' => 'collection_data.edit', 'uses' => 'Collection_data@edit']);
    Route::post('/update/{id}', ['as' => 'collection_data.update', 'uses' => 'Collection_data@update']);
    Route::post('/delete/{id}', ['as' => 'collection_data.delete', 'uses' => 'Collection_data@delete']);
    Route::post('/delete_img/{id}', ['as' => 'collection_data.delete_img', 'uses' => 'Collection_data@delete_img']);
    Route::get('/getListAsPdf', ['as' => 'collection_data.getListAsPdf', 'uses' => 'Collection_data@getListAsPdf']);
    Route::get('/getListAsXls', ['as' => 'collection_data.getListAsXls', 'uses' => 'Collection_data@getListAsXls']);
    Route::get('/getAutocomplete', ['as' => 'collection_data.getAutocomplete', 'uses' => 'Collection_data@getAutocomplete']);
    Route::get('/updateStatus/{id}', ['as' => 'collection_data.updateStatus', 'uses' => 'Collection_data@updateStatus']);
    Route::get('/logActivity/{id}', ['as' => 'collection_data.logActivity', 'uses' => 'Collection_data@logActivity']);
});
