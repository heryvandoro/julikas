<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Game extends Model
{
    use SoftDeletes;
    protected $table = "games";
    public function game_sessions(){
        return $this->hasMany("App\Models\GameSession", "game_id", "id");
    }
}
