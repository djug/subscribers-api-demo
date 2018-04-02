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

Route::group(['namespace' => 'Api', 'middleware' => ['apiKey.auth']], function () {
    Route::get('subscribers', 'SubscribersController@all');
    Route::post('subscribers', 'SubscribersController@create');
    Route::get('subscribers/{id}', 'SubscribersController@get');
    Route::put('subscribers/{id}', 'SubscribersController@update');
    Route::delete('subscribers/{id}', 'SubscribersController@delete');

    Route::get('fields', 'FieldsController@all');
    Route::post('fields', 'FieldsController@create');
    Route::get('fields/{id}', 'FieldsController@get');
    Route::put('fields/{id}', 'FieldsController@update');
    Route::delete('fields/{id}', 'FieldsController@delete');
});
