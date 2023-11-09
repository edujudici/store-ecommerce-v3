<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Seller\MercadoLivreNotificationService;
use Illuminate\Http\Request;

class MercadoLivreNotificationController extends Controller
{
    public function store(Request $request)
    {
        debug(['debug notifications mercado livre' => $request->all()]);
        return $this->_callService(
            MercadoLivreNotificationService::class,
            'store',
            $request
        );
    }
}
