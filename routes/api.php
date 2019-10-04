<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/cruds/create', 'HomeController@store');
Route::get('/cruds/edit/{id}', 'HomeController@edit');
Route::post('/cruds/update/{id}', 'HomeController@update');
Route::post('/cruds/updateDone/{id}', 'HomeController@updateDone');
Route::post('/cruds/delete/{id}', 'HomeController@delete');
Route::get('/cruds/{id}', 'HomeController@read');
