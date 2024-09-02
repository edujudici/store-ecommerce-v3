<?php

namespace App\Services\MerchantCenter;

use App\Models\ProductMerchantCenterHistory;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class ProductMerchantCenterHistoryService extends BaseService
{
    private $productMerchantCenterHistory;

    public function __construct(ProductMerchantCenterHistory $productMerchantCenterHistory)
    {
        $this->productMerchantCenterHistory = $productMerchantCenterHistory;
    }

    public function index(): Collection
    {
        return $this->productMerchantCenterHistory->all();
    }

    public function store($loadDate, $total, $accountTitle): ProductMerchantCenterHistory
    {
        return $this->productMerchantCenterHistory->create([
            'pmh_total' => $total,
            'pmh_account_title' => $accountTitle,
            'created_at' => $loadDate,
        ]);
    }
}
