<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class GameSessionQuestion extends Model
{
    use SoftDeletes;
    protected $table = "game_session_questions";
  
    public function question(){
        return $this->belongsTo("App\Models\Question", "question_id", "id");
    }
}
