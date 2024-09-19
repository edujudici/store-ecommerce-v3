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

    public function loadProductsAll($request): array
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

        return $response;
    }

    public function getProductsAll(): array
    {
        return $this->merchantCenterService->index();
    }

    public function loadProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));
        $response = $this->merchantCenterService->addProduct($this->fillProductData($product));
        $this->storeProductData($product, $response);
        return [
            'id' => $response['id'],
            'kind' => $response['kind'],
        ];
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

        $response = $this->merchantCenterService->updateProduct(
            $product->merchantCenter->prm_product_id,
            $this->fillProductData($product)
        );
        return [
            'id' => $response['id'],
            'kind' => $response['kind'],
        ];
    }

    public function deleteProduct($request): void
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->merchantCenter->prm_product_id)) {
            throw new BusinessError('Product merchant center not found');
        }
        $this->merchantCenterService->destroy($product->merchantCenter->prm_product_id);

        $product->merchantCenter()->delete();
    }

    private function resolveStrategy(string $type): ProductStrategyInterface
    {
        if ($type === self::METHOD_TYPE_INSERT) {
            return new InsertProductStrategy($this->merchantCenterService);
        }

        return new UpdateProductStrategy($this->merchantCenterService);
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
            ->whereIn('pro_sku', ['MLB4413461306']);

        if ($type === self::METHOD_TYPE_INSERT) {
            return $query->doesntHave('merchantCenter')
                ->get();
        }

        return $query->with('merchantCenter')
            ->whereHas('merchantCenter')
            ->get();
    }

    private function prepareProducts($type, Collection $products): array
    {
        return $products->map(function ($product) use ($type) {
            $data = $this->fillProductData($product);
            if ($type === self::METHOD_TYPE_UPDATE) {
                $data['productId'] = $product->merchantCenter->prm_product_id;
            }
            return $data;
        })->toArray();
    }

    private function fillProductData(Product $product): array
    {
        return [
            'offerId' => $product->pro_sku,
            'title' => $product->pro_title,
            'description' => $product->pro_description_long,
            'price' => $product->pro_price,
            'imageLink' => $product->pro_secure_thumbnail,
            'link' => "{$this->baseLinkShopEdit}?sku={$product->pro_sku}",
            'additionalImageLinks' => array_column($product->pictures->toArray(), 'pic_secure_url'),
        ];
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
        $entriesFiltered = array_filter($response['entries'], function ($entry) {
            return !isset($entry['errors']);
        });
        $data = array_map(function ($entry) use ($now) {
            return [
                'pro_sku' => $entry['product']['offerId'],
                'prm_product_id' => $entry['product']['id'],
                'prm_product_kind' => $entry['kind'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }, $entriesFiltered);

        DB::table('products_merchantcenter')->insert($data);

        $this->productMerchantCenterHistoryService
            ->store($now, count($response['entries']), 'MerchantCenter');
    }
}
