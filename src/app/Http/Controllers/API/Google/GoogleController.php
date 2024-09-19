<?php

namespace App\Http\Controllers\API\Google;

use App\Http\Controllers\Controller;
use App\Services\Google\GoogleService;
use App\Services\Google\ProductGoogleHistoryService;
use App\Services\Google\ProductGoogleService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{

    public function redirectToGoogle(Request $request)
    {
        return $this->_callService(
            GoogleService::class,
            'getAuthUrl',
            null
        );
    }

    public function handleGoogleCallback(Request $request)
    {
        return $this->_callService(
            GoogleService::class,
            'handleGoogleCallback',
            $request
        );
    }

    public function getProductsAll(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'getProductsAll',
            $request
        );
    }

    public function getSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'getProduct',
            $request
        );
    }

    public function multipleProducts(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'loadProductsAll',
            $request
        );
    }

    public function singleProduct(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'loadProduct',
            $request
        );
    }

    public function updateSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'updateProduct',
            $request
        );
    }

    public function deleteSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductGoogleService::class,
            'deleteProduct',
            $request
        );
    }

    public function historyProduct(Request $request)
    {
        return $this->_callService(
            ProductGoogleHistoryService::class,
            'index',
            $request
        );
    }
}
