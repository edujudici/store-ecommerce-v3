<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductRelated;
use Illuminate\Database\Eloquent\Collection;

class ProductRelatedService extends BaseService
{
    private $productRelated;

    public function __construct(ProductRelated $productRelated)
    {
        $this->productRelated = $productRelated;
    }

    public function index($request = null): Collection
    {
        $queryProduct = $this->productRelated
            ->selectRaw('products.*')
            ->join(
                'products',
                'products.pro_sku',
                'products_related.pro_sku_related'
            )
            ->whereNull('products.deleted_at');
        if ($request && $request->has('sku')) {
            $queryProduct->where(
                'products_related.pro_sku',
                $request->input('sku')
            );
        }
        $query = $this->productRelated
            ->selectRaw('products.*')
            ->join(
                'products',
                'products.pro_sku',
                'products_related.pro_sku'
            )
            ->whereNull('products.deleted_at')
            ->unionAll($queryProduct);
        if ($request && $request->has('sku')) {
            $query->where(
                'products_related.pro_sku_related',
                $request->input('sku')
            );
        }
        return $query->limit(12)->get();
    }

    public function indexFormat($request): array
    {
        return [
            'products' => $this->index($request),
        ];
    }

    public function store(Product $product, $request): void
    {
        $products = $request->input('products', []);
        if (count($products) > 0) {
            $product->productsRelated()->delete();
            $product->productsRelated()->createMany($products);
        }
    }
}
