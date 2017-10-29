<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Helpers\BOT;

class ProductController extends Controller
{
    public function index(){
      $data = Product::all();
      foreach($data as $d){
         $d->user->detail = BOT::makeRequest("GET", "profile/".$d->user->id_line);
      }
      return view("product.index", compact(['data']));
    }
}
