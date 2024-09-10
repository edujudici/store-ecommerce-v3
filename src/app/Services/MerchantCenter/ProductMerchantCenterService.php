<?php

namespace App\Services\MerchantCenter;

use App\Exceptions\BusinessError;
use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductMerchantCenterService extends BaseService
{
    private $product;
    private $productContext;
    private $productMerchantCenterHistoryService;
    private $merchantCenterService;
    private $baseLinkShopEdit;

    private const METHOD_TYPE_INSERT = 'insert';
    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(
        Product $product,
        ProductContext $productContext,
        ProductMerchantCenterHistoryService $productMerchantCenterHistoryService,
        MerchantCenterService $merchantCenterService
    ) {
        $this->product = $product;
        $this->productContext = $productContext;
        $this->productMerchantCenterHistoryService = $productMerchantCenterHistoryService;
        $this->merchantCenterService = $merchantCenterService;
        $this->baseLinkShopEdit = route('site.shop.detail');
    }

    public function loadProducts($request): void
    {
        if (config('app.env') !== 'production') {
            throw new BusinessError('Action not allowed for this environment');
        }

        $type = $request->input('type');
        $products = $this->getProductsWithImages($type);
        if ($products->isEmpty()) {
            throw new BusinessError('Not exists products to insert');
        }
        $productsPrepared = $this->prepareProducts($type, $products);

        // strategy define
        $strategy = $this->resolveStrategy($type);
        $this->productContext->setStrategy($strategy);

        // strategy execute
        $response = $this->productContext->executeStrategy($productsPrepared);

        $this->storeMultipleData($response);
    }

    public function loadProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));
        $response = $this->merchantCenterService->addProduct($this->prepareProductData($product));
        $this->storeProductData($product, $response);
        return $response;
    }

    public function getProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->merchantCenter->prm_product_id)) {
            throw new BusinessError('Product merchant center not found');
        }

        return $this->merchantCenterService->findById($product->merchantCenter->prm_product_id);
    }

    public function updateProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->merchantCenter->prm_product_id)) {
            throw new BusinessError('Product merchant center not found');
        }

        return $this->merchantCenterService->updateProduct(
            $product->merchantCenter->prm_product_id,
            $this->prepareProductData($product)
        );
    }

    public function deleteProduct($request): void
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->merchantCenter->prm_product_id)) {
            throw new BusinessError('Product merchant center not found');
        }
        $this->merchantCenterService->destroy($product->merchantCenter->prm_product_id);
    }

    private function prepareProducts($type, Collection $products): array
    {
        return $products->map(function ($product) use ($type) {
            $data = $this->prepareProductData($product);
            if ($type === self::METHOD_TYPE_UPDATE) {
                $data['productId'] = $product->merchantCenter->prm_product_id;
            }
            return $data;
        })->toArray();
    }

    private function prepareProductData(Product $product): array
    {
        return [
            'offerId' => $product->pro_sku,
            'title' => $product->pro_title,
            'description' => $product->pro_description_long,
            'price' => $product->pro_price,
            'imageLink' => $product->pro_secure_thumbnail,
            'link' => "{$this->baseLinkShopEdit}?sku={$product->pro_sku}",
            'additionalImageLinks' => $this->loadImages($product->pictures->toArray()),
        ];
    }

    private function findProductBySkuWithImages($sku): Product
    {
        return $this->product->where('pro_sku', $sku)
            ->with(['pictures', 'merchantCenter'])
            ->firstOrFail();
    }

    private function getProductsWithImages($type): Collection
    {
        $query = $this->product
            ->with('pictures')
            ->whereIn('pro_sku', ['MLB2726446616']);

        if ($type === self::METHOD_TYPE_INSERT) {
            return $query->doesntHave('merchantCenter')
                ->get();
        }

        return $query->with('merchantCenter')
            ->whereHas('merchantCenter')
            ->get();
    }

    private function loadImages(array $images): array
    {
        return array_column($images, 'pic_secure_url');
    }

    private function resolveStrategy(string $type): ProductStrategyInterface
    {
        if ($type === self::METHOD_TYPE_INSERT) {
            return new InsertProductStrategy($this->merchantCenterService);
        }

        return new UpdateProductStrategy($this->merchantCenterService);
    }

    private function storeProductData(Product $product, array $response): void
    {
        $product->merchantCenter()->updateOrCreate(
            ['prm_product_id' => $response['id']],
            ['prm_product_kind' => $response['kind']]
        );
    }

    private function storeMultipleData(array $response): void
    {
        $now = date('Y-m-d H:i:s');
        $data = array_map(function ($entry) use ($now) {
            return [
                'pro_sku' => $entry['product']['offerId'],
                'prm_product_id' => $entry['product']['id'],
                'prm_product_kind' => $entry['kind'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }, $response['entries']);

        DB::table('products_merchantcenter')->insert($data);

        $this->productMerchantCenterHistoryService
            ->store($now, count($response['entries']), 'MerchantCenter');
    }
}
