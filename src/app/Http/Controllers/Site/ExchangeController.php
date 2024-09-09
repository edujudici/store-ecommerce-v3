<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class ExchangeController extends Controller
{
    public function index()
    {
        return view('site.exchange');
    }
}
