<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Services\Google\GoogleService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function index()
    {
        return view('painel.googleLoad');
    }

    public function handleGoogleCallback(Request $request)
    {
        $response = $this->_callService(
            GoogleService::class,
            'handleGoogleCallback',
            $request
        );

        return redirect()->route('painel.google.index')
            ->with('callbackResponse', $response);
    }
}
