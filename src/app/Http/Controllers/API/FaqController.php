<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Communication\FaqService;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(FaqService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(FaqService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(FaqService::class, 'destroy', $request);
    }
}
