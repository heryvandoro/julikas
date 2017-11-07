<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\BOT;
use App\Helpers\STR;
use App\Helpers\Constants;
use App\Models\Game;

class IntroController extends Controller
{
    public static function doReplyIntro($request){
      $temp = STR::clean(strtolower($request['message']['text']));
      if($temp=="intro"){ 
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => Constants::$INTRO_GLOBAL)
        ));
      }else{
        $temp = explode("-", $temp);
        if(count($temp)==2){
          self::doReplyIntroGame($request);
        }else{
          BOT::replyMessages($request['replyToken'], array(
            array("type" => "text","text" => Constants::$NOT_FOUND)
          ));
        }
      }
    }
  
    public static function doReplyIntroGame($request){
        $temp = STR::clean(strtolower($request['message']['text']));
        $temp = explode("-", $temp);
        $data = Game::where("game_name", $temp[1])->first();
        $result = "";
        if($data==null){
           $result = Constants::$NOT_FOUND;
        }else{
           $result = $data->description;
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $result)
        ));
    }
}
