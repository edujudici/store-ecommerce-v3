<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Seller\SellerService;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(SellerService::class, 'index', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(SellerService::class, 'destroy', $request);
    }

    public function search(Request $request)
    {
        return $this->_callService(SellerService::class, 'search', $request);
    }
}
