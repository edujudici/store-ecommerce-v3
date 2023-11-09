<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Painel\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(CompanyService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(CompanyService::class, 'store', $request);
    }
}
