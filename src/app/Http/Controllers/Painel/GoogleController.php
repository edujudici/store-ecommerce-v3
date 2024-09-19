<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class GoogleController extends Controller
{
    public function index()
    {
        return view('painel.googleLoad');
    }
}
