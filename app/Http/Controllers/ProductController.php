<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Helpers\BOT;

class ProductController extends Controller
{
    public function index(){
      $data = Product::all();
      foreach($data as $d){
         $d->user->detail = BOT::getProfile($d->user->id_line);
      }
      return view("product.index", compact(['data']));
    }
    public function doReplyProducts($request){
      $data = Product::all()->take(5);
      $messages = array();
      foreach($data as $d){
         array_push($messages, array(
              "type" => "text",
              "text" => $d->product_name." - ".$d->price
         ));
      }
      BOT::replyMessages($request['replyToken'], $messages);
    }
}
