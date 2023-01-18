<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('painel.faq');
    }
}
