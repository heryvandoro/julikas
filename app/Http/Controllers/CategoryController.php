<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CategoryParent;
use App\Helpers\BOT;

class CategoryController extends Controller
{
    public function index(){
      $data = CategoryParent::all();
      return view("category.index", compact('data'));
    }
  
    public static function doReplyCategory($request){
      $data = CategoryParent::all()->take(5);
      $messages = array();
      $item = array();
      foreach($data as $d){
         array_push($item, array(
            "title"=> "Category",
            "text"=> $d->category_parent_name,
            "actions"=> [
                            [
                                "type"=> "postback",
                                "label"=> "View Sub Category",
                                "data"=> $d->id
                            ],
                       ]
            ));
      }
      array_push($messages, array(
          "type" => "template",
          "altText"=> "Parent Category",
          "template" => [
              "type" => "carousel",
              "columns" => $item
          ])
      );
      BOT::replyMessages($request['replyToken'], $messages);
    }
  
    public static function doReplySubCategory($request){
      $data = CategoryParent::with(['categories'])->find($request['postback']['data']);
      $messages = array();
      $item = array();
      foreach($data->categories as $d){
         array_push($item, array(
            "title"=> "Category",
            "text"=> $d->category_name,
            "actions"=> [
                            [
                                "type"=> "postback",
                                "label"=> "View Products",
                                "data"=> $d->id
                            ],
                       ]
            ));
      }
      array_push($messages, array(
          "type" => "template",
          "altText"=> "Sub Category",
          "template" => [
              "type" => "carousel",
              "columns" => $item
          ])
      );
      BOT::replyMessages($request['replyToken'], $messages);
    }
}
