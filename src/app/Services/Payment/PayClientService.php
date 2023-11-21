<?php

namespace App\Services\Payment;

use App\Services\BaseService;
use MercadoPago\Client\MerchantOrder\MerchantOrderClient;
use MercadoPago\Client\Payment\PaymentClient;

class PayClientService extends BaseService implements PayClientInterface
{
    public function getPaymentClient(): PaymentClient
    {
        return new PaymentClient();
    }

    public function getMerchantOrderClient(): MerchantOrderClient
    {
        return new MerchantOrderClient();
    }
}
