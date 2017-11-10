<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameSession;
use App\Models\GameSessionUser;
use App\Helpers\BOT;
use App\Helpers\STR;
use App\Helpers\Constants;
use Carbon\Carbon;
use App\Models\User;

class GameController extends Controller
{
    public function index(){
      $data = Game::all();
      return view("game.index", compact(['data']));
    }
  
    public function detail($id){
      $data = Game::with(["game_sessions"])->find($id);
      foreach($data->game_sessions as $d){
        try{
          $d->user = BOT::getProfile($d->starter_id);
        }catch(\Exception $e){
          $d->user = BOT::getGroupMemberProfile($d->group_id, $d->starter_id);
        }
      }
      if($data!=null){
        return view("game.detail", compact(['data']));
      }else{
        return redirect()->back();
      }
    }
  
    public static function doCreateGame($request){
        $temp = STR::clean(strtolower($request['message']['text']));
        if(isset($request['source']['groupId'])){
          $groupId = $request['source']['groupId'];
          $starterId = $request['source']['userId'];

          $temp = explode("-", $temp);
          $result = "";
          if(count($temp)!=2){
              $result = Constants::$NOT_FOUND;
              $result.= ' Silahkan ketik "/create namagame" untuk memulai game.';
          }else{
              $data = Game::where("game_name", $temp[1])->first();
              if($data==null){
                 $result = Constants::$NOT_FOUND;
                 $result.= " Game yang anda maksud tidak ditemukan.";
              }else{
                 $active_session = GameSession::where("group_id", $groupId)->where("status", "!=", 2)->get();
                 if(count($active_session)!=0){
                   $mess = 'Game : '.Game::find($active_session->first()->game_id)->game_name.' sedang aktif. Tidak dapat memulai game lain. Silahkan kirim "/join" untuk bergabung dalam game.';
                 }else{
                   //start game
                   $new = new GameSession();
                   $new->game_id = $data->id;
                   $new->starter_id = $starterId;
                   $new->group_id = $groupId;
                   $new->status = 0;
                   $new->save();
                   $mess = 'Game telah dimulai, silahkan balas "/join" untuk mulai bermain.';
                 }
                 BOT::replyMessages($request['replyToken'], array(
                  array("type" => "text","text" => $mess)
                 ));
              }
          }
        }else{
          $result = "Maaf, game hanya bisa dimulai dalam group.";
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $result)
        ));
    }
    
    public static function doJoinGame($request){
        $groupId = $request['source']['groupId'];
        $userId = $request['source']['userId'];
        $current_user = User::where("id_line", $userId)->get()->first();
        $mess = "";
        if($current_user==null){
          $mess = "Mohon maaf ".BOT::getGroupMemberProfile($groupId, $userId)->displayName.", kamu belum add JuliKas sebagai teman. Add dulu ya baru main!";
        }else{
          //search active session
          $current_session = GameSession::getSession($groupId, 0)->first();
          if($current_session==null){
            $now_session = GameSession::getSession($groupId, 1)->first();
            if($now_session!=null){
                $mess = "Game sedang berjalan, tidak dapat join.";
            }else{
                $mess = "Tidak ada game yang sedang aktif saat ini.";
            }
          }else{
            $current_session_detail = GameSessionUser::where("game_session_id", $current_session->id)->where("id_line", $userId)->get()->first();
            if($current_session_detail==null){
              $temp = new GameSessionUser();
              $temp->game_session_id = $current_session->id;
              $temp->id_line = $userId;
              $temp->save();
              $mess = "OK ".BOT::getGroupMemberProfile($groupId, $userId)->displayName.", selamat bergabung. Kamu sudah join ke game.";
            }else{
              $mess = "Anda telah tergabung dalam game ".Game::find($current_session->game_id)->game_name.".";
            }
          }
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $mess)
        ));
    }
  
    public static function doCancelGame($request){
        $groupId = $request['source']['groupId'];
        $userId = $request['source']['userId'];
        $active_session = GameSession::getSession($groupId, 1)->first();
        $pending_session = GameSession::getSession($groupId, 0)->first();
        if($active_session && !$pending_session){
          $mess = "Game sedang berjalan, tidak dapat melakukan cancel.";
        }else if(!$active_session && $pending_session){
          if($pending_session->starter_id==$userId){
            $pending_session->status = 2;
            $pending_session->save();
            $pending_session->delete();
            $mess = "Game telah berhasil dicancel.";
          }else{
            $mess = "Tidak bisa melakukan cancel game. Hanya starter game yang dapat melakukannya.";
          }
        }else{
          $mess = "Tidak ada session yang sedang aktif.";
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $mess)
        ));
    }
  
    public static function taskCancel(){
        $data = GameSession::where("status", 0)->with(['game_session_details'])->get();
        foreach($data as $d){
          $now = Carbon::now();
          $diff = Carbon::parse($d->created_at)->diffInMinutes($now);
          //after 5 minutes
          if($diff>=5){
              $d->status = 2;
              $d->save();
              BOT::pushMessages($d->group_id, array(
                array("type" => "text","text" => 'Game telah expired. Silahkan kirim "/create namagame" untuk memulai game baru.')
              ));
              $d->delete();
          }
        }
    }
  
    public static function doStartGame($request){
        $groupId = $request['source']['groupId'];
        $userId = $request['source']['userId'];
        $mess = "";
        $pending_session = GameSession::getSession($groupId, 0)->first();
        if($pending_session==null){
          $active_session = GameSession::getSession($groupId, 1)->first();
          if($active_session==null){
            $mess = "Tidak ada session aktif saat ini.";  
          }else{
            $mess = "Game sedang berjalan, tidak dapat melakukan start lagi.";
          }
        }else{
          $temp = GameSessionUser::where("game_session_id", $pending_session->id)->get();
          if($temp->count()<2){
            $mess = "Minimal 2 pemain untuk memulai game.";
          }else{
            if($pending_session->starter_id!=$userId){
              $mess = "Maaf, game hanya dapat dimulai oleh starter.";
            }else{
              $pending_session->status = 1;
              $pending_session->save();
              //$mess = "Game telah dimulai!";
              KejarController::doSendQuestion($groupId);
            }
          }
        }
        BOT::replyMessages($request['replyToken'], array(
          array("type" => "text","text" => $mess)
        ));
    }
}
