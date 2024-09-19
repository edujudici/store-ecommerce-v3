<?php

namespace App\Services\Google;

interface ProductStrategyInterface
{
    public function execute(array $products): array;
}
