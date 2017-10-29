<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = "products";
  
    public function user(){
       return $this->belongsTo("App\Models\User", "user_id", "id");
    }
  
    public function category(){
       return $this->belongsTo("App\Models\Category", "category_id", "id");
    }
}
