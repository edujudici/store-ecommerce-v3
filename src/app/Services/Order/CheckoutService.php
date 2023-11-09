<?php

namespace App\Services\Order;

use App\Services\BaseService;
use App\Services\User\AddressService;
use App\Services\User\VoucherService;
use Illuminate\Support\Str;

class CheckoutService extends BaseService
{
    private $cartService;
    private $addressService;
    private $preferenceService;
    private $orderService;
    private $voucherService;

    public function __construct(
        CartService $cartService,
        AddressService $addressService,
        PreferenceService $preferenceService,
        OrderService $orderService,
        VoucherService $voucherService
    ) {
        $this->cartService = $cartService;
        $this->addressService = $addressService;
        $this->preferenceService = $preferenceService;
        $this->orderService = $orderService;
        $this->voucherService = $voucherService;
    }

    public function store($request): string
    {
        $dataAddress = $request->input('address');
        $cart = $this->cartService->index();

        $this->voucherService->applyVoucher($cart, $request);
        $address = $this->addressService->store($cart['user'], $dataAddress);
        return $this->preferenceAndOrder($address, $cart);
    }

    private function preferenceAndOrder($address, $cart): string
    {
        if ($cart['total'] > 0) {
            $preference = $this->preferenceService->create($address, $cart);
            $this->orderService->create($preference, $cart, $address);
            return $preference['init_point'];
        }
        $preference = [
            'id' => $id = (string) Str::uuid(),
            'init_point' => route('site.payment.confirmation', ['success'])
            . '?' . http_build_query(['preference_id' => $id]),
        ];
        $this->orderService->create($preference, $cart, $address, true);
        return $preference['init_point'];
    }
}
