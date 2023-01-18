<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PictureService;
use Illuminate\Http\Request;

class PictureController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(PictureService::class, 'index', $request);
    }
}
