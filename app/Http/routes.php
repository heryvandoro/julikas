<?php

Route::post('/', "RequestController@index");

//LOGS
Route::get('/logs', "LogController@index");
Route::get('/logs/last/{count?}', "LogController@count");