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
use Carbon\Carbon;

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
       $temp = GameSessionQuestion::where("game_session_id", $session_id)->get()->count();
       if($temp>=5){
         $sess = GameSession::find($session_id);
         BOT::pushMessages($sess->group_id, array(
          array("type"=>"text", "text"=>"Game telah berakhir! Thankyou petrik :D")
         ));
         $sess->status = 2;
         $sess->save();
         return $sess->delete();
       }
       do{
          $result = Question::orderBy(DB::raw('RAND()'))->get()->first();
       }while(GameSessionQuestion::where("game_session_id", $session_id)->where("question_id", $result->id)->get()->first()!=null);
       $data = new GameSessionQuestion();
       $data->game_session_id = $session_id;
       $data->question_id = $result->id;
       $data->save();
    }
    
    public static function taskSkipAndCancel(){
        $data = GameSession::where("status", 1)->with(["game_session_questions"])->get();
        foreach($data as $d){
          $now = Carbon::now();
          $temp = $d->game_session_questions->last();
          $diff = Carbon::parse($temp->created_at)->diffInMinutes($now);
          //after 3 minutes
          if($diff>=3){
              if($d->game_session_questions->count()<5){
                BOT::pushMessages($d->group_id, array(
                  array("type"=>"text", "text"=>"Question expired, ganti pertanyaan!")
                ));
                self::randomQuestion($d->id);
                self::doSendQuestion($d->group_id);
              }else{
                self::randomQuestion($d->id);
              }
          }
        }
    }
}
