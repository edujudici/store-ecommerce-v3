<?php

namespace App\Services;

use App\Models\Product;

class LoadProductService extends BaseService
{
    public const PRODUCT_EXTERNAL = 1;

    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function store($request): Product
    {
        return $this->product->updateOrCreate([
            'pro_sku' => $request['pro_sku'],
        ], $request);
    }

    public function storeProducts($products): void
    {
        $this->product->insert($products);
    }

    public function destroy($sellerId)
    {
        $this->product
            ->where('pro_seller_id', $sellerId)
            ->where('pro_external', self::PRODUCT_EXTERNAL)
            ->delete();
    }
}
