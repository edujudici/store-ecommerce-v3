<?php

namespace App\Services\Google;

class InsertProductStrategy implements ProductStrategyInterface
{
    protected $googleService;

    private const METHOD_TYPE_INSERT = 'insert';

    public function __construct(GoogleService $googleService)
    {
        $this->googleService = $googleService;
    }

    public function execute(array $products): array
    {
        return $this->googleService->createOrUpdateProducts(self::METHOD_TYPE_INSERT, $products);
    }
}
