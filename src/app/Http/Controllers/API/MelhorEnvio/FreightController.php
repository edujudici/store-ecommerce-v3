<?php

namespace App\Http\Controllers\API\MelhorEnvio;

use App\Http\Controllers\Controller;
use App\Services\Freight\CalculateService;
use Illuminate\Http\Request;

class FreightController extends Controller
{
    public function calculate(Request $request)
    {
        return $this->_callService(
            CalculateService::class,
            'calculate',
            $request
        );
    }
}
