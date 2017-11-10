<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GameSessionQuestion;
use App\Models\GameSession;
use App\Helpers\BOT;

class KejarController extends Controller
{
    public static function doSendQuestion($groupId){
        $active_session = GameSession::getSession($groupId, 1)->first();
        if($active_session!=null){
           $active_questions = GameSessionQuestion::where("game_session_id", $active_session->id)->with(['question'])->get();
           $que = $active_questions->last();
           $result = "";
           $result .= $active_questions->count().". ".$que->question->text;
           BOT::pushMessages($active_session->group_id, array(
              array("type"=>"text", "text"=>$result)
           ));
        }
    }
}
