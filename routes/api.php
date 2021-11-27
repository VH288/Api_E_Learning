<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');
Route::put('verify/{id}','Api\UserController@verified');
Route::put('update/{id}','Api\UserController@update');

Route::get('catatan','Api\CatatanController@index');
Route::get('catatan/{id}','Api\CatatanController@show');
Route::post('catatan','Api\CatatanController@store');
Route::put('catatan/{id}','Api\CatatanController@update');
Route::delete('catatan/{id}','Api\CatatanController@destroy');


