<?php

namespace App\Services\Seller;

use App\Services\BaseService;
use App\Services\ProductService;

class LoadPictureService extends BaseService
{
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function loadPictures($sku, $pictures): void
    {
        debug('load pictures to the product sku: ' . $sku);
        $product = $this->productService->findBySku($sku);
        $this->store($product, $pictures);
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
}
