<?php

namespace App\Services\Google;

use App\Models\ProductGoogleHistory;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class ProductGoogleHistoryService extends BaseService
{
    private $productGoogleHistory;

    public function __construct(ProductGoogleHistory $productGoogleHistory)
    {
        $this->productGoogleHistory = $productGoogleHistory;
    }

    public function index(): Collection
    {
        return $this->productGoogleHistory->all();
    }

    public function store($loadDate, $total, $accountTitle): ProductGoogleHistory
    {
        return $this->productGoogleHistory->create([
            'pgh_total' => $total,
            'pgh_account_title' => $accountTitle,
            'created_at' => $loadDate,
        ]);
    }
}
