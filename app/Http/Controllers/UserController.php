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
  
    public static function doFollow($request){
      $data = User::withTrashed()->where("id_line", $request["source"]["userId"])->first();
      if($data!=null){
        User::withTrashed()->find($data->id)->restore();
      }else{
        $data = new User();
        if($request!=null && $request["source"]["type"]=="user"){
          $data->id_line = $request["source"]["userId"];
        }
        $data->save(); 
      }
    }
  
    public static function doUnfollow($request){
      $data = User::where("id_line", $request["source"]["userId"])->first();
      if($data!=null){
        User::find($data->id)->delete();
      }
    }
}
