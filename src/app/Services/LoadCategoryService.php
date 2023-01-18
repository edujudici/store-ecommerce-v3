<?php

namespace App\Services;

use App\Api\MercadoLibre;
use App\Models\Category;
use App\Models\Product;

class LoadCategoryService extends BaseService
{
    private $product;

    public function __construct(
        Product $product,
        Category $category,
        MercadoLibre $apiMercadoLibre
    ) {
        $this->product = $product;
        $this->category = $category;
        $this->apiMercadoLibre = $apiMercadoLibre;
    }

    public function organizeCategories()
    {
        debug('load categories to products');
        $allCategories = $this->product
            ->whereNotNull('pro_category_id')
            ->groupBy('pro_category_id')
            ->pluck('pro_category_id');
        $allCategories->each(function ($item) {
            $this->store($item);
        });
    }

    public function store($categoryId)
    {
        $response = $this->apiMercadoLibre->getDetailCategory($categoryId);
        $this->category->firstOrCreate([
            'cat_id_secondary' => $response->id,
        ], [
            'cat_title' => $response->name,
        ]);
    }
}
