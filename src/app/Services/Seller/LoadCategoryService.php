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

    public function organizeCategories($mlAccountId)
    {
        debug('Executing of the job LoadCategory');
        $allCategories = $this->product
            ->where('pro_seller_id', $mlAccountId)
            ->whereNotNull('pro_category_id')
            ->groupBy('pro_category_id')
            ->pluck('pro_category_id');
        $allCategories->each(function ($item) use ($mlAccountId) {
            $this->store($item, $mlAccountId);
        });
    }

    public function store($categoryId, $mlAccountId)
    {
        $response = $this->apiMercadoLibre->getDetailCategory($categoryId);
        $this->category->firstOrCreate([
            'cat_id_secondary' => $response->id,
            'cat_seller_id' => $mlAccountId,
        ], [
            'cat_title' => $response->name,
        ]);
    }
}
