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
                                "data"=> "test"
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
}
