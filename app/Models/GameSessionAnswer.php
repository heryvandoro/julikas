<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class GameSessionAnswer extends Model
{
    use SoftDeletes;
    protected $table = "game_session_answers";
  
    public function answer(){
        return $this->belongsTo("App\Models\Answer", "answer_id", "id");
    }
}
