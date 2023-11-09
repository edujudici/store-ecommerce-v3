<?php

namespace App\Services\User;

use App\Exceptions\BusinessError;
use App\Models\Voucher;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class VoucherService extends BaseService
{
    private $voucher;

    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    public function index(): array
    {
        return [
            'vouchers' => $this->voucher->all(),
            'status' => $this->voucher::getStatus(),
        ];
    }

    public function findByUser($request): Collection
    {
        return $this->voucher->where('user_uuid', $request->input('uuid'))
            ->where('vou_status', $this->voucher::STATUS_ACTIVE)
            ->get();
    }

    public function findById($request): Voucher
    {
        return $this->voucher->findOrFail($request->input('id'));
    }

    public function store($request): Voucher
    {
        $params = $this->getParameters($request);
        $this->validateFields($params);

        return $this->voucher->updateOrCreate([
            'vou_id' => $request->input('id'),
        ], $params);
    }

    public function destroy($request): bool
    {
        $voucher = $this->findById($request);
        return $voucher->delete();
    }

    public function valid($request): Voucher
    {
        $voucher = $this->voucher->where('vou_code', $request->input('code'))
            ->first();
        if (is_null($voucher)) {
            throw new BusinessError('Vale de desconto inválido.');
        }
        if (! is_null($voucher->vou_applied_date)) {
            throw new BusinessError('Vale de desconto já foi utilizado.');
        }
        if (! is_null($voucher->vou_expiration_date)) {
            if ($voucher->vou_expiration_date < date('Y-m-d')) {
                $voucher->update([
                    'vou_status' => $this->voucher::STATUS_EXPIRED,
                ]);
                throw new BusinessError('Vale de desconto expirado.');
            }
        }
        return $voucher;
    }

    public function applyVoucher(&$cart, $request): void
    {
        if (! is_null($cart['voucher'])) {
            $request->merge(['code' => $cart['voucher']]);
            $voucher = $this->valid($request);
            $voucher->update([
                'vou_applied_date' => date('Y-m-d'),
                'vou_status' => $this->voucher::STATUS_APPLIED,
            ]);
            $this->saveRemainingValue($voucher, $cart);
            $this->applyVoucherValue($voucher, $cart);
        }
    }

    private function saveRemainingValue($voucher, $cart): void
    {
        if ($voucher->vou_value > $cart['subtotal']) {
            $this->voucher->create([
                'vou_id_base' => $voucher->vou_id,
                'user_uuid' => $cart['user']->uuid,
                'vou_value' => $voucher->vou_value - $cart['subtotal'],
                'vou_code' => randomText(3) . '-' . randomText(4),
                'vou_description' => 'Voucher gerado automaticamente',
            ]);
        }
    }

    private function applyVoucherValue($voucher, &$cart): void
    {
        $voucherValue = $voucher->vou_value;
        foreach ($cart['products'] as $key => $product) {
            $price = $product['price'];
            if ($price > $voucherValue) {
                $cart['products'][$key]['newPrice'] = $price - $voucherValue;
                break;
            }
            $voucherValue -= $price;
            $cart['products'][$key]['newPrice'] = 0;
        }
    }

    private function validateFields($request)
    {
        $rules = [
            'vou_value' => 'required|numeric',
            'vou_expiration_date' => 'nullable|date',
        ];
        $titles = [
            'vou_value' => 'Valor',
            'vou_expiration_date' => 'Data de expiração',
        ];
        $this->_validate($request, $rules, $titles);
    }

    private function getParameters($request)
    {
        $params = $request->all();
        if (! $request->has('id')) {
            $params['vou_code'] = randomText(3) . '-' . randomText(4);
        }
        return $params;
    }
}
