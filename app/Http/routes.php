<?php

Route::post('/', "RequestController@index");

//LOGS
Route::get('/logs', "LogController@index");
Route::get('/logs/last/{count?}', "LogController@count");

//Dashboard
Route::get("/", "DashboardController@index");
Route::get("/products", "ProductController@index");
Route::get("/category", "CategoryController@index");
Route::get("/users", "UserController@index");
Route::get("/groups", "GroupController@index");
Route::get("/games", "GameController@index");
Route::get("/games/{game_id}", "GameController@detail");