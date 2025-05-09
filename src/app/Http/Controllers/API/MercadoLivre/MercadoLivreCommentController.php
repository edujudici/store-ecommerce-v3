<?php

namespace App\Http\Controllers\API\MercadoLivre;

use App\Http\Controllers\Controller;
use App\Services\Seller\MercadoLivreCommentService;
use Illuminate\Http\Request;

class MercadoLivreCommentController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(
            MercadoLivreCommentService::class,
            'index',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(
            MercadoLivreCommentService::class,
            'answer',
            $request
        );
    }

    public function destroy(Request $request)
    {
        return $this->_callService(
            MercadoLivreCommentService::class,
            'destroy',
            $request
        );
    }
}
