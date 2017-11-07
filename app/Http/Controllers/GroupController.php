<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Helpers\BOT;

class GroupController extends Controller
{
    public function index(){
      $data = Group::all();
      return view("group.index", compact(['data']));
    }
  
    public static function doJoin($request){
      $data = Group::withTrashed()->where("id_line", $request["source"]["groupId"])->first();
      if($data!=null){
        Group::withTrashed()->find($data->id)->restore();
      }else{
        $data = new Group();
        if($request!=null && $request["source"]["type"]=="group"){
          $data->id_line = $request["source"]["groupId"];
        }
        $data->save(); 
      }
    }
    public static function doLeave($request){
      $data = Group::where("id_line", $request["source"]["groupId"])->first();
      if($data!=null){
        Group::find($data->id)->delete();
      }
    }
}
