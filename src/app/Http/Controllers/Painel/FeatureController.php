<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class FeatureController extends Controller
{
    public function index()
    {
        return view('painel.feature');
    }
}
