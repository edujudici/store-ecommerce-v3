<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BannerService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(BannerService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(BannerService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(BannerService::class, 'destroy', $request);
    }
}
