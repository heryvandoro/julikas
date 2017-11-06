<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Group extends Model
{
    use SoftDeletes;
    protected $table = "groups";
}
