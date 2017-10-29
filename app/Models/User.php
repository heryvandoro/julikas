<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    protected $table = "users";
}
