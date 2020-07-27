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

Route::post('/sing-up', 'UserAuthController@singUpProcess');
Route::post('/sing-in', 'UserAuthController@singInProcess');
Route::post('/create', 'MerchandiseController@merchandiseCreatProcess');

Route::post('/test', 'UserAuthController@showdata');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/{id}', 'TaskController@show');
    Route::post('/tasks', 'TaskController@store');
    Route::put('/tasks/{id}', 'TaskController@update');
    Route::delete('/tasks/{id}', 'TaskController@destroy');
});