<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\GameSessionQuestion;
use App\Models\GameSession;
use App\Models\GameSessionAnswer;
use App\Models\GameSessionUser;
use App\Helpers\BOT;
use App\Models\Answer;
use App\Models\Question;
use DB;

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
           $finish = true;
           foreach($que->question->answers as $q){
              if(in_array(array('answer_id'=>$q->id), $all_answers)){
                 $temp = Answer::find($q->id);
                 $result .= "- ".ucfirst($temp->text)." (".$temp->point.")\n";
              }else{
                 $result .= "_________________\n";
                 $finish = false;
              }
           }
           
           BOT::pushMessages($active_session->group_id, array(
              array("type"=>"text", "text"=>$result)
           ));
          
           if($finish){
             self::randomQuestion($active_session->id);
             BOT::pushMessages($active_session->group_id, array(
                array("type"=>"text", "text"=>"Soal berikutnya!")
             ));
             return self::doSendQuestion($active_session->group_id);
           }
        }
    }
  
    public static function doJawab($request){
      $groupId = $request['source']['groupId'];
      $userId = $request['source']['userId'];
      $active_session = GameSession::getSession($groupId, 1)->first();
      if($active_session!=null){
        $session_users = GameSessionUser::where("game_session_id", $active_session->id)->where("id_line", $userId)->get()->first();
        if($session_users!=null){
          $active_questions = GameSessionQuestion::where("game_session_id", $active_session->id)->with(['question.answers'])->get()->last();
          $user_answer = str_replace("/jawab ",  "", trim(strtolower($request['message']['text'])));
          $temp = Answer::where("text", $user_answer)
                  ->where("question_id", $active_questions->question_id)
                  ->first();
          if($temp!=null){
            $ans = GameSessionAnswer::where("game_session_id", $active_session->id)->where("answer_id", $temp->id)->get()->first();
            if($ans==null){
              $new_ans = new GameSessionAnswer();
              $new_ans->game_session_id = $active_session->id;
              $new_ans->answer_id = $temp->id;
              $new_ans->save();
              return self::doSendQuestion($active_session->group_id);
            }else{
              $mess = "Jawaban sama petrik!";
            }
          }else{
            $mess = "Jawaban salah petrik!";
          }
        }else{
          $mess = "Anda belum join petrik!";
        }
        BOT::pushMessages($active_session->group_id, array(
          array("type"=>"text", "text"=>$mess)
        ));
      }
    }
  
    public static function randomQuestion($session_id){
       $result = Question::orderBy(DB::raw('RAND()'))->get()->first();
       $data = new GameSessionQuestion();
       $data->game_session_id = $session_id;
       $data->question_id = $result->id;
       $data->save();
    }
}
