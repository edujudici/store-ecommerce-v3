<?php

namespace App\Services;

use App\Models\Product;

class PictureService extends BaseService
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index($request): array
    {
        $product = $this->product
            ->where('pro_sku', $request->input('sku'))
            ->firstOrFail();
        return $product->pictures->toArray();
    }

    public function store($product, $paths): void
    {
        $pictures = $this->prepareCreate($product->pro_sku, $paths);
        if (count($pictures) > 0) {
            $product->pro_image = $paths[0];
            $product->save();
            $product->pictures()->delete();
            $product->pictures()->createMany($pictures);
        }
    }

    private function prepareCreate($sku, $paths): array
    {
        return array_map(static function ($path) use ($sku) {
            return [
                'pro_sku' => $sku,
                'pic_image' => $path,
            ];
        }, $paths);
    }
}
