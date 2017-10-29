<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;

class RequestController extends Controller
{
    public function index(Request $request){
      //save log
      $data = new Log();
      $data->messages = json_encode($request->all());
      $data->save();
      
      $source = $request->events[0];
      
      switch($source["type"]){
          case "follow" : $this->doFollow($source);
          break;
          case "unfollow" : $this->doUnfollow($source);
          break;
      }
      
      return response()->json(["status"=>200]);
    }
  
    public function doFollow($request){
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
  
    public function doUnfollow($request){
      $data = User::where("id_line", $request["source"]["userId"])->first();
      if($data!=null){
        User::find($data->id)->delete();
      }
    }
}