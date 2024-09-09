<?php

namespace App\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Jobs\LoadCategory;
use App\Jobs\LoadProduct;
use App\Jobs\LoadProductDescription;
use App\Jobs\LoadProductPicture;
use App\Jobs\LoadProductRelated;
use App\Services\BaseService;
use App\Traits\ProductTransformable;
use Illuminate\Http\Request;

class LoadMultipleService extends BaseService
{
    use ProductTransformable;

    private const LIMIT = 20;

    private $loadProductService;
    private $loadHistoryService;
    private $apiMercadoLibre;
    private $mercadoLivreService;

    public function __construct(
        LoadProductService $loadProductService,
        LoadHistoryService $loadHistoryService,
        MercadoLibre $apiMercadoLibre,
        MercadoLivreService $mercadoLivreService
    ) {
        $this->loadProductService = $loadProductService;
        $this->loadHistoryService = $loadHistoryService;
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->mercadoLivreService = $mercadoLivreService;
    }

    public function dispatchProducts($request): void
    {
        $mlAccountId = $request->input('mlAccountId');
        $mlAccountTitle = $request->input('mlAccountTitle');
        $loadDate = date('Y-m-d H:i:s');
        $newRequest = Request::create('/', 'POST', [
            'id' => $mlAccountId,
        ]);
        $mlAccount = $this->mercadoLivreService->findById($newRequest);
        $data = $this->apiMercadoLibre->getMultipleProducts($mlAccount, 0, self::LIMIT);

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
        $newRequest = Request::create('/', 'POST', [
            'id' => $mlAccountId,
        ]);
        $mlAccount = $this->mercadoLivreService->findById($newRequest);
        $data = $this->apiMercadoLibre->getMultipleProductsDetails(
            $mlAccount,
            $skus,
            ['pictures']
        );
        $this->loadProductService->storeProducts(
            $this->prepareProducts($data, $loadDate)
        );
        $this->completeProductFields($data, $mlAccount->mel_user_id);

        if (count($skus) === self::LIMIT) {
            $offset += self::LIMIT;
            $data = $this->apiMercadoLibre->getMultipleProducts(
                $mlAccount,
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
            LoadProductRelated::dispatch($product->body->id, $product->body->item_relations)->onQueue('products');
        }
        LoadCategory::dispatch($mlAccountId)->onQueue('products');
    }
}
