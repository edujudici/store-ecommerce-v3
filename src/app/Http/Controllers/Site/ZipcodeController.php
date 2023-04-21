<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ZipcodeService;

class ZipcodeController extends Controller
{
    public function index($zipcode)
    {
        return $this->_callService(ZipcodeService::class, 'index', $zipcode);
    }
}
