<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
          case "leave" : GroupController::doLeave($source);
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
        IntroController::doReplyIntro($request);
      }elseif(STR::startsWith($keyword, "/start")){
        GameController::doStartGame($request);
      }elseif(STR::startsWith($keyword, "/join")){
        GameController::doJoinGame($request);
      }elseif(STR::startsWith($keyword, "apakah")){
        KerangController::doReplyKerang($request);
      }else{
        UnknownController::doUnknown($request);
      }
    } 
}