<?php

namespace App\Services;

use App\Api\MercadoLibre;

class LoadDescriptionService extends BaseService
{
    private $apiMercadoLibre;
    private $productService;

    public function __construct(
        MercadoLibre $apiMercadoLibre,
        ProductService $productService
    ) {
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->productService = $productService;
    }

    public function loadDescriptions($skus): void
    {
        debug('load long description to the products');
        foreach ($skus as $sku) {
            $this->store($sku);
        }
    }

    public function store($sku): void
    {
        $description = $this->apiMercadoLibre->getDescriptionProduct($sku);
        $product = $this->productService->findBySku($sku);
        if (isset($description->plain_text)) {
            $product->pro_description_long = $description->plain_text;
            $product->save();
        }
    }
}
