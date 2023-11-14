<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', ['uses' => '\App\Modules\Home\Controllers\Home@index']);
Route::get('/form', ['as' => 'form', 'uses' => '\App\Modules\Home\Controllers\Home@form']);
Route::post('/store', ['as' => 'store', 'uses' => '\App\Modules\Home\Controllers\Home@store']);
Route::get('/monitoring', ['as' => 'monitoring', 'uses' => '\App\Modules\Home\Controllers\Home@monitoring']);