<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class MercadoLivreController extends Controller
{
    private const MERCADOLIVRE_URL = 'https://auth.mercadolivre.com.br';

    public function index()
    {
        return view('painel.mercadolivreHome');
    }

    public function accounts()
    {
        $clientId = env('MERCADO_LIVRE_CLIENT_ID');
        $redirect = env('MERCADO_LIVRE_REDIRECT_URI');
        $authorization = self::MERCADOLIVRE_URL
            . "/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirect}";

        return view('painel.mercadolivre', compact('authorization'));
    }

    public function comments()
    {
        return view('painel.comment');
    }

    public function commentsHistory()
    {
        return view('painel.commentHistory');
    }

    public function productsLoad()
    {
        return view('painel.mercadolivreLoad');
    }

    public function answers()
    {
        return view('painel.mercadolivreAnswer');
    }

    public function sellers()
    {
        return view('painel.seller');
    }
}
