<?php

namespace App\Services\MerchantCenter;

interface ProductStrategyInterface
{
    public function execute(array $products): array;
}
