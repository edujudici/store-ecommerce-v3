<?php

namespace App\Services\Seller;

use App\Services\BaseService;
use App\Services\Product\ProductRelatedService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class LoadProductRelatedService extends BaseService
{
    private $productService;
    private $productRelatedService;

    public function __construct(
        ProductService $productService,
        ProductRelatedService $productRelatedService
    ) {
        $this->productService = $productService;
        $this->productRelatedService = $productRelatedService;
    }

    public function loadProductsRelated($sku, $productsRelated): void
    {
        debug('Executing of the job LoadProductRelatedService for sku: ' . $sku);
        $product = $this->productService->findBySku($sku);
        $productsRelated = $this->prepareCreate($sku, $productsRelated);

        $request = Request::create('/', 'POST', [
            'products' => $productsRelated,
        ]);

        $this->productRelatedService->store($product, $request);
    }

    private function prepareCreate($sku, $list): array
    {
        return array_map(static function ($item) use ($sku) {
            return [
                'pro_sku' => $sku,
                'pro_sku_related' => $item->id,
                'prr_external' => true
            ];
        }, $list);
    }
}
