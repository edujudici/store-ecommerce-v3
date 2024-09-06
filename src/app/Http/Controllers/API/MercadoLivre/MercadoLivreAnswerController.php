<?php

namespace App\Http\Controllers\API\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Services\Seller\MercadoLivreAnswerService;
use Illuminate\Http\Request;

class MercadoLivreAnswerController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            MercadoLivreAnswerService::class,
            'index',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(
            MercadoLivreAnswerService::class,
            'store',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(
            MercadoLivreAnswerService::class,
            'destroy',
            $request
        );
    }
}
