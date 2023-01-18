<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('painel.user');
    }
}
