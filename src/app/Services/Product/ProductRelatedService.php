<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductRelated;
use App\Services\BaseService;
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
        $query = $this->productRelated
            ->selectRaw('products.*')
            ->join(
                'products',
                'products.pro_sku',
                'products_related.pro_sku_related'
            )
            ->whereNull('products.deleted_at');
        if ($request && $request->has('sku')) {
            $query->where(
                'products_related.pro_sku',
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
