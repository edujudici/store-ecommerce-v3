<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index()
    {
        return view('painel.newsletter');
    }
}
