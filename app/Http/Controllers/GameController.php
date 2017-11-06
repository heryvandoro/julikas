<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Game;

class GameController extends Controller
{
    public function index(){
      $data = Game::all();
      return view("game.index", compact(['data']));
    }
}
