<?php

namespace App\Services\Seller;

use App\Api\MercadoLibre;
use App\Jobs\LoadCategory;
use App\Jobs\LoadProduct;
use App\Jobs\LoadProductDescription;
use App\Jobs\LoadProductPicture;
use App\Services\BaseService;
use App\Traits\ProductTransformable;

class LoadMultipleService extends BaseService
{
    use ProductTransformable;

    private const LIMIT = 20;

    private $loadProductService;
    private $loadHistoryService;
    private $apiMercadoLibre;

    public function __construct(
        LoadProductService $loadProductService,
        LoadHistoryService $loadHistoryService,
        MercadoLibre $apiMercadoLibre
    ) {
        $this->loadProductService = $loadProductService;
        $this->loadHistoryService = $loadHistoryService;
        $this->apiMercadoLibre = $apiMercadoLibre;
    }

    public function dispatchProducts($request): void
    {
        $mlAccountId = $request->input('mlAccountId');
        $mlAccountTitle = $request->input('mlAccountTitle');
        $loadDate = date('Y-m-d H:i:s');
        $data = $this->apiMercadoLibre->getMultipleProducts($mlAccountId, 0, self::LIMIT);

        $total = $data->paging->total ?? 0;
        debug('Dispatch products total: ' . $total . ' to account: ' . $mlAccountId);
        if ($total > 0) {
            $this->loadProductService->destroy(
                $data->seller_id
            );
            $skus = $data->results ?? [];
            LoadProduct::dispatch($loadDate, $mlAccountId, $skus)->onQueue('products');
        }
        $this->loadHistoryService->store($loadDate, $total, $mlAccountTitle);
    }

    public function loadProducts($loadDate, $mlAccountId, $skus, $offset = 0): void
    {
        debug('Job LoadProduct on date ' . $loadDate . ' to mercado livre account ' . $mlAccountId
            . ' for skus list: ' . json_encode($skus));

        $data = $this->apiMercadoLibre->getMultipleProductsDetails(
            $mlAccountId,
            $skus,
            ['pictures']
        );
        $this->loadProductService->storeProducts(
            $this->prepareProducts($data, $loadDate)
        );
        $this->completeProductFields($data, $mlAccountId);

        if (count($skus) === self::LIMIT) {
            $offset += self::LIMIT;
            $data = $this->apiMercadoLibre->getMultipleProducts(
                $mlAccountId,
                $offset,
                self::LIMIT
            );
            $skus = $data->results ?? [];
            $total = count($skus);
            debug('Load more products total: ' . $total . ' to account: ' . $mlAccountId);
            if ($total > 0) {
                $this->loadProducts($loadDate, $mlAccountId, $skus, $offset);
            }
        }
    }

    private function prepareProducts($products, $loadDate)
    {
        return array_map(static function ($data) use ($loadDate) {
            return self::prepareProduct($data->body, $loadDate);
        }, $products);
    }

    private function completeProductFields($products, $mlAccountId): void
    {
        foreach ($products as $product) {
            LoadProductDescription::dispatch($product->body->id)->onQueue('description');
            LoadProductPicture::dispatch($product->body->id, $product->body->pictures)->onQueue('pictures');
        }
        LoadCategory::dispatch($mlAccountId)->onQueue('products');
    }
}
