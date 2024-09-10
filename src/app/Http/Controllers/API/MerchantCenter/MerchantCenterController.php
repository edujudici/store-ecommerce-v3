<?php

namespace App\Http\Controllers\API\MerchantCenter;

use App\Http\Controllers\Controller;
use App\Services\MerchantCenter\ProductMerchantCenterHistoryService;
use App\Services\MerchantCenter\ProductMerchantCenterService;
use Illuminate\Http\Request;

class MerchantCenterController extends Controller
{
    public function multipleProducts(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'loadProducts',
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

    public function getSingleProduct(Request $request)
    {
        return $this->_callService(
            ProductMerchantCenterService::class,
            'getProduct',
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
