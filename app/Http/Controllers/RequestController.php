<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use App\Helpers\BOT;
use App\Helpers\STR;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryParent;

class RequestController extends Controller
{
    public function index(Request $request){
      //save log
      $data = new Log();
      $data->messages = json_encode($request->all());
      $data->save();
      
      $source = $request->events[0];
      
      switch($source["type"]){
          case "follow" : $this->doFollow($source);
          break;
          case "unfollow" : $this->doUnfollow($source);
          break;
          case "message" : $this->doMessages($source);
          break;
      }
      
      return response()->json(["status"=>200]);
    }
  
    public function doFollow($request){
      $data = User::withTrashed()->where("id_line", $request["source"]["userId"])->first();
      if($data!=null){
        User::withTrashed()->find($data->id)->restore();
      }else{
        $data = new User();
        if($request!=null && $request["source"]["type"]=="user"){
          $data->id_line = $request["source"]["userId"];
        }
        $data->save(); 
      }
    }
  
    public function doUnfollow($request){
      $data = User::where("id_line", $request["source"]["userId"])->first();
      if($data!=null){
        User::find($data->id)->delete();
      }
    }
  
    public function doMessages($request){
      $keyword = trim(strtolower($request['message']['text']));
      switch($keyword){
        case "/products":
          $this->doReplyProducts($request);
          break;
        case "/category":
          $this->doReplyCategory($request);
          break;
      }
      
      if(STR::startsWith($keyword, "apakah")){
        $this->doReplyGameYesNo($request);
      }else{
        $this->doUnknown($request);
      }
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
  
    public function doReplyCategory($request){
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
  
    public function doReplyGameYesNo($request){
      $temp = STR::clean($request['message']['text']);
      $result = "";
      $arr1 = str_split($temp);
      $sum = 0;
      foreach($arr1 as $item){
         $sum += ord($item);
      }
      if($sum%2==0){
        $result = "ya";
      }else{
        $result = "tidak";
      }
      BOT::replyMessages($request['replyToken'], array(
        array("type" => "text","text" => $result)
      ));
    }
  
    public function doUnknown($request){
      $temp = STR::clean($request['message']['text']);
      $result = "";
      switch($temp){
        case "hai-spongebob" :
          $result = "waduh petrik!!!";
          break;
        case "ruang-berapa" :
          $result = "coba cek 724 aja";
          break;
        case "ada-ruang" :
          $result = "ada";
          break;
      }
      if(!empty($result)){
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $result)
        ));
      }
    }
}