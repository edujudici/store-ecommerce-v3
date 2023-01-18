<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('site.cart');
    }

    public function data(Request $request)
    {
        return $this->_callService(CartService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(CartService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(CartService::class, 'destroy', $request);
    }

    public function update(Request $request)
    {
        return $this->_callService(CartService::class, 'update', $request);
    }
}
