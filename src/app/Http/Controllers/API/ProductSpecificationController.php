<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductSpecificationService;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            ProductSpecificationService::class,
            'index',
            $request
        );
    }
}
