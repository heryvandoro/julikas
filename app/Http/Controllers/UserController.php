<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Helpers\BOT;

class UserController extends Controller
{
    public function index(){
      $data = User::all();
      foreach($data as $d){
         $d->detail = BOT::makeRequest("GET", "profile/".$d->id_line);
      }
      return view("user.index", compact(['data']));
    }
}
