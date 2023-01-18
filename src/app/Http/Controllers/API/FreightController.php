<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FreightService;
use Illuminate\Http\Request;

class FreightController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getCodes()
    {
        return $this->_callService(FreightService::class, 'getCodes', []);
    }

    public function calculate(Request $request)
    {
        return $this->_callService(
            FreightService::class,
            'calculate',
            $request
        );
    }
}
