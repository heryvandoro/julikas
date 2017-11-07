<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\STR;
use App\Helpers\BOT;

class UnknownController extends Controller
{
    public static function doUnknown($request){
      $temp = STR::clean(strtolower($request['message']['text']));
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
