<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\BOT;
use App\Helpers\STR;

class KerangController extends Controller
{
    public static function doReplyKerang($request){
      $temp = STR::clean(strtolower($request['message']['text']));
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
}
