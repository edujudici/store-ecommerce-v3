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

    public function loadDescription($sku): void
    {
        debug('load description to the product sku: ' . $sku);

        $data = $this->apiMercadoLibre->getDescriptionProduct($sku);
        $product = $this->productService->findBySku($sku);
        if (isset($data->plain_text)) {
            $product->pro_description_long = $data->plain_text;
            $product->save();
        }
    }
}
