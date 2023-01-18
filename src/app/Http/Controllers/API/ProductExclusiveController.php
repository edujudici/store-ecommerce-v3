<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductExclusiveService;
use Illuminate\Http\Request;

class ProductExclusiveController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            ProductExclusiveService::class,
            'index',
            $request
        );
    }
}
