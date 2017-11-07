<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Helpers\BOT;

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
}
