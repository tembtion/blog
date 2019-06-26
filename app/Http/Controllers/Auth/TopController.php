<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopController extends Controller
{
    public function home()
    {
        return view('auth.top.home');
    }

    public function index()
    {
        return view('auth.top.index');
    }
}
