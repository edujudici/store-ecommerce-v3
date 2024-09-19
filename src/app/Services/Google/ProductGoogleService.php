<?php

namespace App\Services\Google;

use App\Exceptions\BusinessError;
use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductGoogleService extends BaseService
{
    private $product;
    private $productContext;
    private $productGoogleHistoryService;
    private $googleService;
    private $baseLinkShopEdit;

    private const METHOD_TYPE_INSERT = 'insert';
    private const METHOD_TYPE_UPDATE = 'update';

    public function __construct(
        Product $product,
        ProductContext $productContext,
        ProductGoogleHistoryService $productGoogleHistoryService,
        GoogleService $googleService
    ) {
        $this->product = $product;
        $this->productContext = $productContext;
        $this->productGoogleHistoryService = $productGoogleHistoryService;
        $this->googleService = $googleService;
        $this->baseLinkShopEdit = route('site.shop.detail');
    }

    public function loadProductsAll($request): array
    {
        // if (config('app.env') !== 'production') {
        //     throw new BusinessError('Action not allowed for this environment');
        // }

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

        $this->storeMultipleData($response, $type);

        return $response;
    }

    public function getProductsAll(): array
    {
        return $this->googleService->index();
    }

    public function loadProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));
        $response = $this->googleService->addProduct($this->fillProductData($product));
        $this->storeProductData($product, $response);
        return [
            'id' => $response['id'],
            'kind' => $response['kind'],
        ];
    }

    public function getProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->google->pgo_product_id)) {
            throw new BusinessError('Product Google not found');
        }

        return $this->googleService->findById($product->google->pgo_product_id);
    }

    public function updateProduct($request): array
    {
        $product = $this->findProductBySkuWithImages($request->input('sku'));

        if (!isset($product->google->pgo_product_id)) {
            throw new BusinessError('Product Google not found');
        }

        $response = $this->googleService->updateProduct(
            $product->google->pgo_product_id,
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

        if (!isset($product->google->pgo_product_id)) {
            throw new BusinessError('Product Google not found');
        }
        $this->googleService->destroy($product->google->pgo_product_id);

        $product->google()->delete();
    }

    private function resolveStrategy(string $type): ProductStrategyInterface
    {
        if ($type === self::METHOD_TYPE_INSERT) {
            return new InsertProductStrategy($this->googleService);
        }

        return new UpdateProductStrategy($this->googleService);
    }

    private function findProductBySkuWithImages($sku): Product
    {
        return $this->product->where('pro_sku', $sku)
            ->with(['pictures', 'google'])
            ->firstOrFail();
    }

    private function getProductsWithImages($type): Collection
    {
        $query = $this->product
            ->with('pictures')
            ->whereIn('pro_sku', ['MLB4413461306']);

        if ($type === self::METHOD_TYPE_INSERT) {
            return $query->doesntHave('google')
                ->get();
        }

        return $query->with('google')
            ->whereHas('google')
            ->get();
    }

    private function prepareProducts($type, Collection $products): array
    {
        return $products->map(function ($product) use ($type) {
            $data = $this->fillProductData($product);
            if ($type === self::METHOD_TYPE_UPDATE) {
                $data['productId'] = $product->google->pgo_product_id;
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
        $product->google()->updateOrCreate(
            ['pgo_product_id' => $response['id']],
            ['pgo_product_kind' => $response['kind']]
        );
    }

    private function storeMultipleData(array $response, $type): void
    {
        $now = date('Y-m-d H:i:s');
        $entriesFiltered = array_filter($response['entries'], function ($entry) {
            return !isset($entry['errors']);
        });
        $data = array_map(function ($entry) use ($now) {
            return [
                'pro_sku' => $entry['product']['offerId'],
                'pgo_product_id' => $entry['product']['id'],
                'pgo_product_kind' => $entry['kind'],
                'created_at' => $now,
                'updated_at' => $now
            ];
        }, $entriesFiltered);

        DB::table('products_google')->insert($data);

        $this->productGoogleHistoryService
            ->store($now, count($response['entries']), "Google {$type}");
    }
}
