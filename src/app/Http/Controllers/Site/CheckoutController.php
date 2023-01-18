<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('shopper');
    }

    public function index(Request $request)
    {
        $request->session()->forget('redirect_checkout');
        $data = json_encode($this->_callService(
            CartService::class,
            'index',
            $request
        ));
        return view('site.checkout')->with(compact('data'));
    }

    public function store(Request $request)
    {
        return $this->_callService(CheckoutService::class, 'store', $request);
    }
}
