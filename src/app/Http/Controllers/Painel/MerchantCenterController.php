<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;

class MerchantCenterController extends Controller
{
    public function productsLoad()
    {
        return view('painel.merchantcenterLoad');
    }
}
