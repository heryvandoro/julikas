<?php

Route::post('/', "RequestController@index");

//LOGS
Route::get('/logs', "LogController@index");
Route::get('/logs/last/{count?}', "LogController@count");

//Dashboard
Route::get("/", "DashboardController@index");
Route::get("/products", "ProductController@index");
Route::get("/category", "CategoryController@index");

Route::get("/coba", function(){
  $messages = array(
     
              "type" => "template",
              "template" => [
                "type" => "carousel",
                "columns" => [
                                array(
                                  "title"=> "this is menu",
                                  "text"=> "coba",
                                  "actions"=> [
                                                  [
                                                      "type"=> "postback",
                                                      "label"=> "Buy",
                                                      "data"=> "action=buy&itemid=111"
                                                  ],
                                                  [
                                                      "type"=> "postback",
                                                      "label"=> "Add to cart",
                                                      "data"=> "action=add&itemid=111"
                                                 ],
                                           
                                             ]
                                  )
                              ]
                ]
         
      );
  return $messages;
});