<?php

namespace App\Services\MerchantCenter;

class ProductContext
{
    protected $strategy;

    public function setStrategy(ProductStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function executeStrategy(array $products): array
    {
        return $this->strategy->execute($products);
    }
}
