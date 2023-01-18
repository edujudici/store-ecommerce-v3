<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PayResponseService;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function notification(Request $request)
    {
        return $this->_callService(
            PayResponseService::class,
            'notification',
            $request
        );
    }
}
