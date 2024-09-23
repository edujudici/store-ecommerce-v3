<?php

namespace App\Services\Order;

use App\Services\BaseService;
use App\Services\Product\ProductService;

class CartService extends BaseService
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(): array
    {
        return [
            'user' => auth()->user(),
            'products' => session('cart.products', []),
            'freightServices' => session('cart.freightServices', []),
            'zipcode' => session('cart.zipcode', ''),
            'subtotal' => session('cart.subtotal', 0),
            'freightData' => session('cart.freightData', ''),
            'total' => session('cart.total', 0),
            'voucher' => session('cart.voucher', ''),
            'voucherValue' => session('cart.voucherValue', 0),
        ];
    }

    public function getCartById($productSku): ?array
    {
        return session('cart.products.' . $productSku);
    }

    public function store($request): void
    {
        $productSku = $request->input('sku');
        if ($this->getCartById($productSku)) {
            $qty = session('cart.products.' . $productSku . '.amount') + 1;
            session(['cart.products.' . $productSku . '.amount' => $qty]);
        } else {
            $product = $this->productService->findBySku($productSku);
            $prodAtributes = [
                'id' => $product->pro_id,
                'sku' => $product->pro_sku,
                'title' => $product->pro_title,
                'price' => $product->pro_price,
                'image' => $product->pro_image,
                'thumbnail' => $product->pro_secure_thumbnail,
                'amount' => $request->input('amount', 1)
            ];
            session(['cart.products.' . $productSku => $prodAtributes]);
        }
    }

    public function update($request): array
    {
        foreach ($request->input('products', []) as $value) {
            $productSku = $value['sku'];
            session(
                ['cart.products.' . $productSku . '.amount' => $value['amount']]
            );
        }
        session(['cart.subtotal' => $request->input('subtotal', 0)]);
        session(['cart.zipcode' => $request->input('zipcode', '')]);
        session(
            ['cart.freightServices' => $request->input('freightServices', [])]
        );
        $this->getFreightData($request->input('freightSelected', ''));
        session(['cart.voucher' => $request->input('voucher', null)]);
        session(['cart.voucherValue' => $request->input('voucherValue', 0)]);
        $this->addTotalValue();
        session(['redirect_checkout' => true]);

        return $this->index();
    }

    public function destroy($request): void
    {
        $productSku = $request->input('sku');
        session()->forget('cart.products.' . $productSku);
    }

    private function getFreightData($freightSelected): void
    {
        foreach (session('cart.freightServices', []) as $value) {
            if ($value['code'] === $freightSelected) {
                session(['cart.freightData' => $value]);
                break;
            }
        }
    }

    private function addTotalValue(): void
    {
        $subtotal = session('cart.subtotal') - session('cart.voucherValue');
        $subtotal = $subtotal < 0 ? 0 : $subtotal;
        $freight = session('cart.freightData');
        $freightPrice = isset($freight['price'])
            ? str_replace(',', '.', $freight['price'])
            : 0;
        session(['cart.total' => $subtotal + $freightPrice]);
    }
}
