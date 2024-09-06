<?php

namespace App\Services\MerchantCenter;

class UpdateProductStrategy implements ProductStrategyInterface
{
    protected $merchantCenterService;

    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(MerchantCenterService $merchantCenterService)
    {
        $this->merchantCenterService = $merchantCenterService;
    }

    public function execute(array $products): array
    {
        return $this->merchantCenterService->createOrUpdateProducts(self::METHOD_TYPE_UPDATE, $products);
    }
}
