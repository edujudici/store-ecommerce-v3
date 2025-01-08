<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(UserService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(UserService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(UserService::class, 'destroy', $request);
    }
}
