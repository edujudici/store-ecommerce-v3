<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Communication\NewsletterService;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            NewsletterService::class,
            'index',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(NewsletterService::class, 'store', $request);
    }
}
