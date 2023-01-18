<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class OrderCommentController extends Controller
{
    public function index()
    {
        return view('painel.orderComment');
    }
}
