<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('painel.product');
    }
}
