<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        return view('painel.category');
    }
}
