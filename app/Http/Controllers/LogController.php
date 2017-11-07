<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Log;

class LogController extends Controller
{
    public function index(){
      $data = Log::all();
      foreach($data as $d){
        $d->messages = json_decode($d->messages);
      }
      return $data;
    }
    public function count($count=-1){
      if($count!=-1){
        $data = Log::orderBy("id", "desc")->get()->take($count);
        foreach($data as $d){
          $d->messages = json_decode($d->messages);
        }
      }else{
        $data = Log::all()->last(); 
        $data->messages = json_decode($data->messages);
      }
      
      return $data;
    }
    public static function saveLog($request){
        $data = new Log();
        $data->messages = json_encode($request);
        $data->save();
    }
}
