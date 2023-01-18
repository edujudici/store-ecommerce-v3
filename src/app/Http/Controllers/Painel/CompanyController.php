<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    public function index()
    {
        return view('painel.company');
    }
}
