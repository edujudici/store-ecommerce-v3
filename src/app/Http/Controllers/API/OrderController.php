<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(OrderService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(OrderService::class, 'store', $request);
    }
}
