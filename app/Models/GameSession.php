<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class GameSession extends Model
{
    use SoftDeletes;
    protected $table="game_sessions";
  
    public function scopeGetSession($query, $groupId, $status){
      return $query->where("group_id", $groupId)->where("status", $status)->get()->first();
    }
}
