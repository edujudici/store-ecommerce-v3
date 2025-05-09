<?php

namespace App\Http\Controllers\API\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Services\Seller\MercadoLivreDashboardService;
use App\Services\Seller\MercadoLivreService;
use Illuminate\Http\Request;

class MercadoLivreController extends Controller
{
    public function dashboard(Request $request)
    {
        return $this->_callService(
            MercadoLivreDashboardService::class,
            'index',
            $request
        );
    }

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

    public function getMyInfoData(Request $request)
    {
        return $this->_callService(
            MercadoLivreService::class,
            'getMyInfoData',
            $request
        );
    }
}
