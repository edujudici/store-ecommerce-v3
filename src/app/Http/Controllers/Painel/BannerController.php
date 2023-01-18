<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index()
    {
        return view('painel.banner');
    }
}
