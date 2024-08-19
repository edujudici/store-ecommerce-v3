<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductExclusive;
use App\Services\BaseService;

class ProductExclusiveService extends BaseService
{
    private const PRODUCTS_LIMIT = 4;

    private $productExclusive;

    public function __construct(ProductExclusive $productExclusive)
    {
        $this->productExclusive = $productExclusive;
    }

    public function index($request): array
    {
        $products = $this->productExclusive
            ->with('product')
            ->limit($request->input('amount', 24))
            ->get()
            ->toArray();
        return array_chunk(
            $products,
            self::PRODUCTS_LIMIT
        );
    }

    public function store(Product $product, $request): void
    {
        if ($request->has('isProductExclusive')) {
            $product->exclusiveDeal()->updateOrCreate([]);
        } else {
            $product->exclusiveDeal()->delete();
        }
    }
}
