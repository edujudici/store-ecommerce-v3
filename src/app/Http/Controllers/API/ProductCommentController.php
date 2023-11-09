<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCommentService;
use Illuminate\Http\Request;

class ProductCommentController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            ProductCommentService::class,
            'index',
            $request
        );
    }

    public function indexAll(Request $request)
    {
        return $this->_callService(
            ProductCommentService::class,
            'indexAll',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(
            ProductCommentService::class,
            'store',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(
            ProductCommentService::class,
            'destroy',
            $request
        );
    }
}
