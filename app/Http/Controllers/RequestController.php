<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use App\Models\Group;
use App\Models\Product;
use App\Models\Category;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\CategoryParent;
use App\Helpers\BOT;
use App\Helpers\STR;
use App\Helpers\Constants;

class RequestController extends Controller
{
    public function index(Request $request){
      //save log
      LogController::saveLog($request->all());
      
      $source = $request->events[0];
      
      switch($source["type"]){
        case "follow" : UserController::doFollow($source);
          break;
          case "unfollow" : UserController::doUnfollow($source);
          break;
          case "message" : $this->doMessages($source);
          break;
          case "join" : GroupController::doJoin($source);
          break;
      }
      
      return response()->json(["status"=>200]);
    } 
  
    public function doMessages($request){
      $keyword = trim(strtolower($request['message']['text']));
      //single command (for menu)
      switch($keyword){
        case "/products":
          ProductController::doReplyProducts($request);
          break;
        case "/category":
          CategoryController::doReplyCategory($request);
          break;
      }
      if(STR::startsWith($keyword, "/intro")){
        $this->doReplyIntro($request);
      }elseif(STR::startsWith($keyword, "/start")){
        $this->doStartGame($request);
      }elseif(STR::startsWith($keyword, "/join")){
        $this->doJoinGame($request);
      }elseif(STR::startsWith($keyword, "apakah")){
        $this->doReplyGameYesNo($request);
      }else{
        $this->doUnknown($request);
      }
    }
  
    public function doReplyGameYesNo($request){
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
  
    public function doUnknown($request){
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
  
    public function doReplyIntro($request){
      $temp = STR::clean(strtolower($request['message']['text']));
      if($temp=="intro"){ 
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => Constants::$INTRO_GLOBAL)
        ));
      }else{
        $temp = explode("-", $temp);
        if(count($temp)==2){
          $this->doReplyIntroGame($request);
        }else{
          BOT::replyMessages($request['replyToken'], array(
            array("type" => "text","text" => Constants::$NOT_FOUND)
          ));
        }
      }
    }
  
    public function doReplyIntroGame($request){
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
  
    public function doStartGame($request){
        $temp = STR::clean(strtolower($request['message']['text']));
        $temp = explode("-", $temp);
        $result = "";
        if(count($temp)!=2){
            $result = Constants::$NOT_FOUND;
        }else{
            $data = Game::where("game_name", $temp[1])->first();
            if($data==null){
               $result = Constants::$NOT_FOUND;
            }else{
               //start game
               $new = new GameSession();
               $new->game_id = $data->id;
               $new->starter_id = $request['source']['userId'];
               $new->group_id = $request['source']['groupId'];
               $new->status = 0;
               $new->save();
               $mess = 'Game telah dimulai, silahkan balas "/join '.$data->game_name.'" untuk mulai bermain.';
               BOT::replyMessages($request['replyToken'], array(
                array("type" => "text","text" => $mess)
               ));
            }
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $result)
        ));
    }
    
    public function doJoinGame($request){
      
    }
}