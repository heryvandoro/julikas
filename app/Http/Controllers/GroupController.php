<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Helpers\BOT;

class GroupController extends Controller
{
    public function index(){
      $data = Group::all();
      return view("group.index", compact(['data']));
    }
}
