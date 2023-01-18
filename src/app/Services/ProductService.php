<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService
{
    private const PRODUCTS_LIMIT = 4;

    private $product;
    private $productExclusiveService;
    private $productRelatedService;
    private $productSpecificationService;
    private $pictureService;

    public function __construct(
        Product $product,
        ProductExclusiveService $productExclusiveService,
        ProductRelatedService $productRelatedService,
        ProductSpecificationService $productSpecificationService,
        PictureService $pictureService
    ) {
        $this->product = $product;
        $this->productExclusiveService = $productExclusiveService;
        $this->productRelatedService = $productRelatedService;
        $this->productSpecificationService = $productSpecificationService;
        $this->pictureService = $pictureService;
    }

    public function index($request): array
    {
        $products = $this->product
            ->search($request)
            ->with('exclusiveDeal')
            ->paginate($request->input('amount', 12))
            ->onEachSide(1);
        return [
            'products' => isset($products->toArray()['data'])
                ? $products->toArray()['data']
                : [],
            'pagination' => (string) $products
                ->setPath('')
                ->links(),
        ];
    }

    public function indexFormat($request): array
    {
        return array_chunk(
            $this->index($request)['products'],
            self::PRODUCTS_LIMIT
        );
    }

    public function findByName($request): Collection
    {
        return $this->product
            ->where('pro_title', 'like', '%'. $request->input('term') .'%')
            ->orWhere('pro_sku', $request->input('term'))
            ->limit(5)
            ->get();
    }

    public function findById($request): Product
    {
        return $this->product
            ->with('category')
            ->findOrFail($request->input('id'));
    }

    public function findBySku($sku): Product
    {
        return $this->product->with('category')->with('categoryML')
            ->where('pro_sku', $sku)
            ->firstOrFail();
    }

    public function exists($sku): bool
    {
        return $this->product
            ->where('pro_sku', $sku)
            ->exists();
    }

    public function store($request): Product
    {
        $params = $this->getParameters($request);
        $this->validateFields($params);

        $product = $this->product->updateOrCreate([
            'pro_id' => $request->input('id'),
        ], $params);

        $paths = uploadImages($request);
        $this->pictureService->store($product, $paths);
        $this->productExclusiveService->store($product, $request);
        $this->productRelatedService->store($product, $request);
        $this->productSpecificationService->store($product, $request);
        return $product;
    }

    public function destroy($request): bool
    {
        $product = $this->findById($request);
        return $product->delete();
    }

    private function validateFields($request)
    {
        $rules = [
            'pro_title' => 'required|string|max:255',
            'pro_description' => 'required|string|max:255',
            'pro_price' => 'required|numeric',
            'pro_oldprice' => 'required|numeric',
            'file.*' => 'mimes:jpeg,png,jpg,webp|max:2048',
        ];
        $titles = [
            'pro_title' => 'Título',
            'pro_description' => 'Descrição Breve',
            'pro_price' => 'Preço',
            'pro_oldprice' => 'Preço Original',
            'file' => 'Imagem',
        ];
        $this->_validate($request, $rules, $titles);
    }

    private function getParameters($request)
    {
        $params = $request->all();
        if (! $request->has('id')) {
            $params['pro_sku'] = 'LOC' . randomText(10);
        }
        return $params;
    }
}
