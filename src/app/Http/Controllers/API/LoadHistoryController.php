<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Seller\LoadHistoryService;
use App\Services\Seller\LoadQuestionHistoryService;

class LoadHistoryController extends Controller
{
    public function indexProduct()
    {
        return $this->_callService(
            LoadHistoryService::class,
            'index',
            []
        );
    }

    public function indexQuestion()
    {
        return $this->_callService(
            LoadQuestionHistoryService::class,
            'index',
            []
        );
    }
}
