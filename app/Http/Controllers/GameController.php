<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameSession;
use App\Helpers\BOT;
use App\Helpers\STR;
use App\Helpers\Constants;

class GameController extends Controller
{
    public function index(){
      $data = Game::all();
      return view("game.index", compact(['data']));
    }
  
    public function detail($id){
      $data = Game::with(["game_sessions"])->find($id);
      foreach($data->game_sessions as $d){
        $d->user = BOT::getProfile($d->starter_id);
      }
      if($data!=null){
        return view("game.detail", compact(['data']));
      }else{
        return redirect()->back();
      }
    }
  
    public static function doStartGame($request){
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
    
    public static function doJoinGame($request){
      
    }
}
