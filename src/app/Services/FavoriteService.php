<?php

namespace App\Services;

use App\Exceptions\BusinessError;

class FavoriteService extends BaseService
{
    private $productService;
    private $userSessionService;

    public function __construct(
        ProductService $productService,
        UserSessionService $userSessionService
    ) {
        $this->productService = $productService;
        $this->userSessionService = $userSessionService;
    }

    public function index($userId): array
    {
        $response = $this->userSessionService->findByUser($userId);
        if (is_null($response)) {
            return [];
        }

        $products = json_decode($response->uss_json, true);
        session(['favorite.products' => $products]);
        return $products;
    }

    public function getFavoriteById($productSku): ?array
    {
        return session('favorite.products.' . $productSku);
    }

    public function addFavorite($request): void
    {
        $productSku = $request->input('sku');
        if ($this->getFavoriteById($productSku)) {
            throw new BusinessError('Este produto já esta favoritado,
                consulte sua area de favoritos.');
        }
        $product = $this->productService->findBySku($productSku);
        $prodAtributes = [
            'sku' => $product->pro_sku,
            'title' => $product->pro_title,
            'price' => $product->pro_price,
            'image' => $product->pro_image,
            'thumbnail' => $product->pro_secure_thumbnail,
            'date' => date('Y-m-d H:i:s'),
            'amount' => $request->input('amount', 1),
        ];
        session(['favorite.products.' . $productSku => $prodAtributes]);
        $this->save();
    }

    public function destroy($request): void
    {
        $productSku = $request->input('sku');
        session()->forget('favorite.products.' . $productSku);
        $this->save();
    }

    private function save(): void
    {
        $this->userSessionService->store(
            auth()->user(),
            json_encode(session('favorite.products', []))
        );
    }
}
