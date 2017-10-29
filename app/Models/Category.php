<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = "categories";
}
