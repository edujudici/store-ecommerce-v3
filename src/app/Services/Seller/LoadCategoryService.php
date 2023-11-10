<?php

namespace App\Services\Seller;

use App\Api\MercadoLibre;
use App\Models\Category;
use App\Models\Product;
use App\Services\BaseService;

class LoadCategoryService extends BaseService
{
    private $product;
    private $category;
    private $apiMercadoLibre;

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
        debug('Executing of the job LoadCategory');
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
