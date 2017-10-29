<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SoftDeletes;

class CategoryParent extends Model
{
    use SoftDeletes;
    protected $table = "category_parents";
  
    public function categories(){
        return $this->hasMany("App\Models\Category", "category_parent_id", "id");
    }
}
