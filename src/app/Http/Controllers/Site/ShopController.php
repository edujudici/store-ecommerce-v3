<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_input', '');
        return view('site.shop')->with(compact('search'));
    }

    public function detail($sku = null)
    {
        $exists = $this->_callService(ProductService::class, 'exists', $sku);
        if (! $exists['response']) {
            return abort(404);
        }
        return view('site.detail')->with(compact('sku'));
    }
}
