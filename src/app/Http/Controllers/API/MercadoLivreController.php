<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MercadoLivreService;
use Illuminate\Http\Request;

class MercadoLivreController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            MercadoLivreService::class,
            'index',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(
            MercadoLivreService::class,
            'store',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(
            MercadoLivreService::class,
            'destroy',
            $request
        );
    }

    public function auth(Request $request)
    {
        $this->_callService(
            MercadoLivreService::class,
            'auth',
            $request
        );

        return redirect()->route('painel.mercadolivre.accounts.index');
    }
}
