<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class GameSession extends Model
{
    use SoftDeletes;
    protected $table="game_sessions";
}
