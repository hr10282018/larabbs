<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //个人页面展示
    public function show(User $user){
      return view('users.show',compact('user'));
    }
}

