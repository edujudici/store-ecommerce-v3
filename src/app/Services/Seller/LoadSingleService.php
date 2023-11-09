<?php

namespace App\Services\Seller;

use App\Api\MercadoLibre;
use App\Models\Product;
use App\Services\BaseService;
use App\Traits\ProductTransformable;

class LoadSingleService extends BaseService
{
    use ProductTransformable;

    private $loadProductService;
    private $loadPictureService;
    private $loadDescriptionService;
    private $loadCategoryService;
    private $apiMercadoLibre;
    private $loadDate;

    public function __construct(
        LoadProductService $loadProductService,
        LoadPictureService $loadPictureService,
        LoadDescriptionService $loadDescriptionService,
        LoadCategoryService $loadCategoryService,
        MercadoLibre $apiMercadoLibre
    ) {
        $this->loadProductService = $loadProductService;
        $this->loadPictureService = $loadPictureService;
        $this->loadDescriptionService = $loadDescriptionService;
        $this->loadCategoryService = $loadCategoryService;
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->loadDate = date('Y-m-d H:i:s');
    }

    public function loadProduct($sku): void
    {
        $data = $this->apiMercadoLibre->getSingleProduct($sku, ['pictures']);
        $product = $this->store($data);
        $this->loadPictureService->store($product, $data->pictures);
        $this->loadCategoryService->store($product->pro_category_id);
        $this->loadDescriptionService->loadDescription($sku);
    }

    public function store($data): Product
    {
        return $this->loadProductService->store(
            self::prepareProduct($data, $this->loadDate)
        );
    }
}
