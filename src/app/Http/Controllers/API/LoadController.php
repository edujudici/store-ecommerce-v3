<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Seller\LoadMultipleService;
use App\Services\Seller\LoadQuestionService;
use App\Services\Seller\LoadSingleService;
use Illuminate\Http\Request;

class LoadController extends Controller
{
    public function multipleProducts(Request $request)
    {
        return $this->_callService(
            LoadMultipleService::class,
            'dispatchProducts',
            $request
        );
    }

    public function singleProduct(Request $request)
    {
        return $this->_callService(
            LoadSingleService::class,
            'loadProduct',
            $request->input('sku')
        );
    }

    public function questions()
    {
        return $this->_callService(
            LoadQuestionService::class,
            'dispatchQuestions',
            []
        );
    }
}
