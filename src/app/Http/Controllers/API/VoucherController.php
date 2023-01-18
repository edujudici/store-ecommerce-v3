<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\VoucherService;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(VoucherService::class, 'index', $request);
    }

    public function findByUser(Request $request)
    {
        return $this->_callService(
            VoucherService::class,
            'findByUser',
            $request
        );
    }

    public function store(Request $request)
    {
        return $this->_callService(VoucherService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(VoucherService::class, 'destroy', $request);
    }

    public function valid(Request $request)
    {
        return $this->_callService(VoucherService::class, 'valid', $request);
    }
}
