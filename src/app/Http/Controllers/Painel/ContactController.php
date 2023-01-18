<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        return view('painel.contact');
    }
}
