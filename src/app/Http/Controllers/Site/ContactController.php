<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        return view('site.contact');
    }
}
