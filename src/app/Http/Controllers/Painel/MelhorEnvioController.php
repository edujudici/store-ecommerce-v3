<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class MelhorEnvioController extends Controller
{
    private const SCOPE = 'cart-read cart-write companies-read companies-write coupons-read coupons-write notifications-read orders-read products-read products-write purchases-read shipping-calculate shipping-cancel shipping-checkout shipping-companies shipping-generate shipping-preview shipping-print shipping-share shipping-tracking ecommerce-shipping transactions-read users-read users-write';

    public function index()
    {
        $clientId = env('MELHOR_ENVIO_CLIENT_ID');
        $redirect = env('MELHOR_ENVIO_REDIRECT_URI');
        $url = env('MELHOR_ENVIO_URL');
        $scope = self::SCOPE;
        $authorization = $url . "/oauth/authorize?client_id={$clientId}&redirect_uri={$redirect}&response_type=code&state=imperiodomdf&scope={$scope}";

        return view('painel.melhorenvio', compact('authorization'));
    }
}
