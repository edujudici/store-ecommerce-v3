<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductSpecificationService extends BaseService
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index($request): Collection
    {
        $product = $this->product->where('pro_sku', $request->input('sku'))
            ->firstOrFail();
        return $product->specifications;
    }

    public function store(Product $product, $request): void
    {
        $specifications = $request->input('specifications', []);
        if (count($specifications) > 0) {
            $product->specifications()->delete();
            $product->specifications()->createMany($specifications);
        }
    }
}
