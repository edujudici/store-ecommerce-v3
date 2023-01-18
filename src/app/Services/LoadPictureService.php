<?php

namespace App\Services;

use App\Api\MercadoLibre;

class LoadPictureService extends BaseService
{
    private $apiMercadoLibre;
    private $productService;

    public function __construct(
        MercadoLibre $apiMercadoLibre,
        ProductService $productService
    ) {
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->productService = $productService;
    }

    public function loadPictures($skus): void
    {
        debug('load pictures to the products');
        $products = $this->apiMercadoLibre->getProductsPictures($skus);
        $this->processPictures($products);
    }

    public function store($product, $pictures): void
    {
        $pictures = $this->prepareCreate($product->pro_sku, $pictures);
        if (count($pictures) > 0) {
            $product->pro_thumbnail = $pictures[0]['pic_url'];
            $product->pro_secure_thumbnail = $pictures[0]['pic_secure_url'];
            $product->save();
            $product->pictures()->delete();
            $product->pictures()->createMany($pictures);
        }
    }

    private function prepareCreate($sku, $list): array
    {
        return array_map(static function ($item) use ($sku) {
            return [
                'pro_sku' => $sku,
                'pic_id_secondary' => $item->id,
                'pic_url' => $item->url,
                'pic_secure_url' => $item->secure_url,
                'pic_size' => $item->size,
                'pic_max_size' => $item->max_size,
                'pic_quality' => $item->quality,
            ];
        }, $list);
    }

    private function processPictures($products): void
    {
        foreach ($products as $value) {
            $product = $this->productService->findBySku($value->body->id);
            $this->store($product, $value->body->pictures);
        }
    }
}
