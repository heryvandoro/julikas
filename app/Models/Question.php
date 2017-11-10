<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $table = "questions";
}
