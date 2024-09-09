<?php

namespace App\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Services\BaseService;
use App\Services\Product\ProductService;

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
        debug('Executing of the job LoadProductDescription for sku: ' . $sku);
        $data = $this->apiMercadoLibre->getDescriptionProduct($sku);
        $product = $this->productService->findBySku($sku);
        if (isset($data->plain_text)) {
            $product->pro_description_long = $data->plain_text;
            $product->save();
        }
    }
}
