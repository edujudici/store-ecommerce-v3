<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        return view('painel.brand');
    }
}
