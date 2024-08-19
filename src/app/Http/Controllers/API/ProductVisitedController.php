<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductVisitedService;
use Illuminate\Http\Request;

class ProductVisitedController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            ProductVisitedService::class,
            'index',
            $request
        );
    }
}
