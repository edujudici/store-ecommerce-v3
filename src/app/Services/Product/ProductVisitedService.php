<?php

namespace App\Services\Product;

use App\Models\ProductVisited;
use App\Services\BaseService;

class ProductVisitedService extends BaseService
{
    private const PRODUCTS_LIMIT = 4;
    private $productVisited;

    public function __construct(ProductVisited $productVisited)
    {
        $this->productVisited = $productVisited;
    }

    public function index($request): array
    {
        $products = $this->productVisited
            ->with('product')
            ->limit($request->input('amount', 12))
            ->orderBy('prv_visited', 'desc')
            ->get()
            ->toArray();
        return array_chunk(
            $products,
            self::PRODUCTS_LIMIT
        );
    }

    public function store($sku): void
    {
        $productVisited = $this->productVisited->firstOrCreate([
            'pro_sku' => $sku,
        ], [
            'prv_visited' => 0
        ]);

        $productVisited->prv_visited += 1;
        $productVisited->save();
    }
}
