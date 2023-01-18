<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('painel.order');
    }
}
