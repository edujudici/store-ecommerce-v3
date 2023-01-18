<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return $this->_callService(CategoryService::class, 'index', $request);
    }

    public function store(Request $request)
    {
        return $this->_callService(CategoryService::class, 'store', $request);
    }

    public function destroy(Request $request)
    {
        return $this->_callService(CategoryService::class, 'destroy', $request);
    }
}
