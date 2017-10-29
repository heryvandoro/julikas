<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CategoryParent;

class CategoryController extends Controller
{
    public function index(){
      $data = CategoryParent::all();
      return view("category.index", compact('data'));
    }
}
