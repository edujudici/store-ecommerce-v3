<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Painel\FeatureService;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(FeatureService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(FeatureService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(FeatureService::class, 'destroy', $request);
    }
}
