<?php

namespace App\Services\Payment;

interface PayClientInterface
{
    public function getPaymentClient();
    public function getMerchantOrderClient();
}
