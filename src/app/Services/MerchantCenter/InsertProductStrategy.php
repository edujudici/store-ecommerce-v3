<?php

namespace App\Services\MerchantCenter;

class InsertProductStrategy implements ProductStrategyInterface
{
    protected $merchantCenterService;

    private const METHOD_TYPE_INSERT = 'insert';

    public function __construct(MerchantCenterService $merchantCenterService)
    {
        $this->merchantCenterService = $merchantCenterService;
    }

    public function execute(array $products): array
    {
        return $this->merchantCenterService->createOrUpdateProducts(self::METHOD_TYPE_INSERT, $products);
    }
}
