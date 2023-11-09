<?php

namespace App\Services\Seller;

use App\Models\MercadoLivreProduct;
use App\Services\BaseService;

class MercadoLivreProductService extends BaseService
{
    private $mercadoLivreProduct;

    public function __construct(MercadoLivreProduct $mercadoLivreProduct)
    {
        $this->mercadoLivreProduct = $mercadoLivreProduct;
    }

    public function exists($itemId)
    {
        return $this->mercadoLivreProduct
            ->where('mep_item_id', $itemId)
            ->exists();
    }

    public function store($comment, $product): void
    {
        $comment->product()->create($this->prepareProduct($product));
    }

    private function prepareProduct($product): array
    {
        return [
            'mep_item_id' => $product->id,
            'mep_title' => $product->title ?? null,
            'mep_price' => $product->price ?? null,
            'mep_permalink' => $product->permalink ?? null,
            'mep_secure_thumbnail' => $product->secure_thumbnail ?? null,
        ];
    }
}
