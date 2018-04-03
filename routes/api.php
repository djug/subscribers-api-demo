<?php

use Illuminate\Http\Request;

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
