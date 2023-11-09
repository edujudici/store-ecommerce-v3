<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductRelatedService;
use Illuminate\Http\Request;

class ProductRelatedController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            ProductRelatedService::class,
            'index',
            $request
        );
    }

    public function indexFormat(Request $request)
    {
        return $this->_callService(
            ProductRelatedService::class,
            'indexFormat',
            $request
        );
    }
}
