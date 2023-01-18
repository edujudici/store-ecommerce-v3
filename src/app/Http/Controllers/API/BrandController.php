<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(BrandService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(BrandService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(BrandService::class, 'destroy', $request);
    }
}
