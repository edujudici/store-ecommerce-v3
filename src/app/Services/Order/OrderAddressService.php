<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\BaseService;

class OrderAddressService extends BaseService
{
    public function store(Order $order, $address): void
    {
        $item = $this->prepareAddress($order, $address);
        $order->address()->create($item);
    }

    private function prepareAddress($order, $item)
    {
        return [
            'ord_id' => $order->ord_id,
            'ora_name' => $item->adr_name,
            'ora_surname' => $item->adr_surname,
            'ora_phone' => $item->adr_phone,
            'ora_zipcode' => $item->adr_zipcode,
            'ora_address' => $item->adr_address,
            'ora_number' => $item->adr_number,
            'ora_district' => $item->adr_district,
            'ora_city' => $item->adr_city,
            'ora_complement' => $item->adr_complement,
            'ora_type' => $item->adr_type,
            'ora_uf' => $item->adr_uf,
        ];
    }
}
