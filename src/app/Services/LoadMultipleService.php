<?php

namespace App\Services;

use App\Api\MercadoLibre;
use App\Jobs\LoadCategory;
use App\Jobs\LoadProduct;
use App\Jobs\LoadProductDescription;
use App\Jobs\LoadProductPicture;
use App\Traits\ProductTransformable;
use Exception;

class LoadMultipleService extends BaseService
{
    use ProductTransformable;

    private const LIMIT = 50;

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
        $offset = 0;
        $loadDate = date('Y-m-d H:i:s');
        $mlAccountId = $request->input('mlAccountId');
        $mlAccountTitle = $request->input('mlAccountTitle');
        $data = $this->apiMercadoLibre->getMultipleProducts(0, $mlAccountId, 1);
        $total = $data->paging->total ?? 0;
        if ($total > 0) {
            $this->loadProductService->destroy(
                $data->results[0]->seller->id
            );
            do {
                LoadProduct::dispatch($offset, $loadDate, $mlAccountId)
                    ->onQueue('products');
                $offset += self::LIMIT;
            } while ($offset < $total);
            LoadCategory::dispatch()->onQueue('products');
        }
        $this->loadHistoryService->store($loadDate, $total, $mlAccountTitle);
    }

    public function loadProducts($offset, $loadDate, $mlAccountId): void
    {
        debug('load products on date ' . $loadDate . ' and offset ' . $offset
            . ' to mercado livre account ' . $mlAccountId);
        $data = $this->apiMercadoLibre->getMultipleProducts(
            $offset,
            $mlAccountId
        );
        if (isset($data->results) && count($data->results) > 0) {
            $this->loadProductService->storeProducts(
                $this->prepareProducts($data->results, $loadDate)
            );
            $this->completeProductFields($data->results);
            return;
        }
        throw new Exception(json_encode($data));
    }

    private function completeProductFields($products): void
    {
        $skus = array_map(static function ($data) {
            return $data->id;
        }, $products);

        $skusChunk = array_chunk($skus, 20);

        foreach ($skusChunk as $skus) {
            LoadProductPicture::dispatch($skus)->onQueue('pictures');
            LoadProductDescription::dispatch($skus)->onQueue('descriptions');
        }
    }

    private function prepareProducts($products, $loadDate)
    {
        return array_map(static function ($data) use ($loadDate) {
            return self::prepareProduct($data, $loadDate);
        }, $products);
    }
}
