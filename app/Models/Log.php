<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Log extends Model
{
  use SoftDeletes;
  protected $table = "logs";
}
