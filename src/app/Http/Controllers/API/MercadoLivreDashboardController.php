<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Seller\MercadoLivreDashboardService;
use Illuminate\Http\Request;

class MercadoLivreDashboardController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            MercadoLivreDashboardService::class,
            'index',
            $request
        );
    }
}
