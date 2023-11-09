<?php

namespace App\Services\Seller;

use App\Models\LoadHistory;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class LoadHistoryService extends BaseService
{
    private $loadHistory;

    public function __construct(LoadHistory $loadHistory)
    {
        $this->loadHistory = $loadHistory;
    }

    public function index(): Collection
    {
        return $this->loadHistory->all();
    }

    public function store($loadDate, $total, $accountTitle): LoadHistory
    {
        return $this->loadHistory->create([
            'loh_total' => $total,
            'loh_account_title' => $accountTitle,
            'created_at' => $loadDate,
        ]);
    }
}
