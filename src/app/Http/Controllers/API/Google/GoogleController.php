<?php

namespace App\Http\Controllers\API\Google;

use App\Http\Controllers\Controller;
use App\Services\MerchantCenter\MerchantCenterService;
use App\Services\MerchantCenter\ProductMerchantCenterHistoryService;
use App\Services\MerchantCenter\ProductMerchantCenterService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{

    public function redirectToGoogle(Request $request)
    {
        return $this->_callService(
            MerchantCenterService::class,
            'getAuthUrl',
            null
        );
    }

    public function handleGoogleCallback(Request $request)
    {
        return $this->_callService(
            MerchantCenterService::class,
            'handleGoogleCallback',
            $request
        );
    }

    public function getProductsAll(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'getProductsAll',
            $request
        );
    }

    public function getSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'getProduct',
            $request
        );
    }

    public function multipleProducts(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'loadProductsAll',
            $request
        );
    }

    public function singleProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'loadProduct',
            $request
        );
    }

    public function updateSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'updateProduct',
            $request
        );
    }

    public function deleteSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'deleteProduct',
            $request
        );
    }

    public function historyProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterHistoryService::class,
            'index',
            $request
        );
    }
}
