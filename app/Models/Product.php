<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = "products";
}
