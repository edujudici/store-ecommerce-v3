<?php

namespace App\Services\Google;

class UpdateProductStrategy implements ProductStrategyInterface
{
    protected $googleService;

    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(GoogleService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function execute(array $products): array
    {
        return $this->googleService->createOrUpdateProducts(self::METHOD_TYPE_UPDATE, $products);
    }
}
