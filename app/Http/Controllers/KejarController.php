<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GameSessionQuestion;
use App\Models\GameSession;
use App\Models\GameSessionAnswer;
use App\Helpers\BOT;
use App\Models\Answer;

class KejarController extends Controller
{
    public static function doSendQuestion($groupId){
        $active_session = GameSession::getSession($groupId, 1)->first();
        if($active_session!=null){
           $active_questions = GameSessionQuestion::where("game_session_id", $active_session->id)->with(['question.answers'])->get();
           $all_answers = GameSessionAnswer
                         ::select('answer_id')
                         ->where("game_session_id", $active_session->id)
                         ->get()->toArray();
           $que = $active_questions->last();
           $result = "";
           $result .= $active_questions->count().". ".$que->question->text."\n";
           foreach($que->question->answers as $q){
              if(in_array(array('answer_id'=>$q->id), $all_answers)){
                 $temp = Answer::find($q->id);
                 $result .= "- ".ucfirst($temp->text)." (".$temp->point.")\n";
              }else{
                 $result .= "_________________\n";
              }
           }
           BOT::pushMessages($active_session->group_id, array(
              array("type"=>"text", "text"=>$result)
           ));
        }
    }
  
    public static function doJawab($request){
      $groupId = $request['source']['groupId'];
      $userId = $request['source']['userId'];
      $active_session = GameSession::getSession($groupId, 1)->first();
      if($active_session!=null){
        $active_questions = GameSessionQuestion::where("game_session_id", $active_session->id)->with(['question.answers'])->get()->last();
        $user_answer = str_replace("/jawab ",  "", trim(strtolower($request['message']['text'])));
        $temp = Answer::where("text", $user_answer)
                ->where("question_id", $active_questions->question_id)
                ->first();
        if($temp!=null){
          $new_ans = new GameSessionAnswer();
          $new_ans->game_session_id = $active_session->id;
          $new_ans->answer_id = $temp->id;
          $new_ans->save();
          return self::doSendQuestion($active_session->group_id);
        }else{
          BOT::pushMessages($active_session->group_id, array(
              array("type"=>"text", "text"=>"wadu petrik")
           ));
        }
      }
    }
}
