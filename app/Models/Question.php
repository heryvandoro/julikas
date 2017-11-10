<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $table = "questions";
    
    public function answers(){
        return $this->hasMany("App\Models\Answer", "question_id", "id");
    }
}
