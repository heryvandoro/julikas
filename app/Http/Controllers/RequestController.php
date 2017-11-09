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
        case "/intro" :
          IntroController::doReplyIntro($request);
          break;
        case "/join" :
          GameController::doJoinGame($request);
          break; 
        case "/cancel" :
          GameController::doCancelGame($request);
          break; 
      }
      
      //others pattern
      if(STR::startsWith($keyword, "/intro")){
        IntroController::doReplyIntroGame($request);
      }elseif(STR::startsWith($keyword, "/create")){
        GameController::doCreateGame($request);
      }elseif(STR::startsWith($keyword, "apakah")){
        KerangController::doReplyKerang($request);
      }else{
        UnknownController::doUnknown($request);
      }
    } 
}