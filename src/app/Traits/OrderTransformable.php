<?php

namespace App\Traits;

use MercadoPago\Preference;

trait OrderTransformable
{
    /**
     * Transform the order
     *
     * @param array $preference
     * @param array $cart
     *
     * @return array
     */
    protected static function prepareOrder($preference, $cart, $paid): array
    {
        return [
            'user_id' => $cart['user']->id,
            'preferenceId' => $preference['id'],
            'status' => $paid ? 'paid' : 'new',
            'ord_protocol' => self::generateProtocol(),
            'ord_preference_init_point' => $preference['init_point'],
            'ord_external_reference' => $preference['external_reference'],
            'ord_freight_code' => $cart['freightData']['code'],
            'ord_freight_price' => str_replace(
                ',',
                '.',
                $cart['freightData']['price']
            ),
            'ord_freight_service' => $cart['freightData']['serviceName'],
            'ord_freight_time' => $cart['freightData']['deliveryTime'],
            'ord_total' => $cart['total'],
            'ord_subtotal' => $cart['subtotal'],
            'ord_voucher_code' => $cart['voucher'],
            'ord_voucher_value' => $cart['voucherValue'],
            'ord_promised_date' => self::generatePromisedDate(
                $cart['freightData']['deliveryTime']
            ),
        ];
    }

    /**
     * Generate text the protocol
     *
     * @return string
     */
    private static function generateProtocol()
    {
        $identify = 'V' . randomText(2);
        $randomText = randomText(8);
        return $identify . '-' . $randomText;
    }

    /**
     * Calculate promised date
     *
     * @param string|int $days
     *
     * @return string
     */
    private static function generatePromisedDate($days): string
    {
        return date('Y-m-d', strtotime("+{$days} days"));
    }
}
