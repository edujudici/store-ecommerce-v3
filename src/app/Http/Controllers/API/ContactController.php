<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Communication\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(ContactService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(ContactService::class, 'store', $request);
    }

    public function answer(Request $request)
    {
        return $this->_callService(ContactService::class, 'answer', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(ContactService::class, 'destroy', $request);
    }
}
