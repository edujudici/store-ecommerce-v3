<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderCommentService;
use Illuminate\Http\Request;

class OrderCommentController extends Controller
{
    public function indexAll(Request $request)
    {
        return $this->_callService(
            OrderCommentService::class,
            'indexAll',
            $request
        );
    }

    public function index(Request $request)
    {
        return $this->_callService(
            OrderCommentService::class,
            'index',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(
            OrderCommentService::class,
            'store',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(
            OrderCommentService::class,
            'destroy',
            $request
        );
    }
}
