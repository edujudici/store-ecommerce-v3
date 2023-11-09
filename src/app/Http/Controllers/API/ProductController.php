<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(ProductService::class, 'index', $request);
    }

    public function indexFormat(Request $request)
    {
        return $this->_callService(
            ProductService::class,
            'indexFormat',
            $request
        );
    }

    public function findByName(Request $request)
    {
        return $this->_callService(
            ProductService::class,
            'findByName',
            $request
        );
    }

    public function show(Request $request)
    {
        return $this->_callService(
            ProductService::class,
            'findBySku',
            $request->input('sku')
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(ProductService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(ProductService::class, 'destroy', $request);
    }
}
