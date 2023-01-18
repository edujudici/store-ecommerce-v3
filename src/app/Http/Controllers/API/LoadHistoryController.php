<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\LoadHistoryService;
use App\Services\LoadQuestionHistoryService;

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
