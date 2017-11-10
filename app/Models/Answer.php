<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;
    protected $table = "answers";
}
