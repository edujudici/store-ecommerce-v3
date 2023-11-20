<?php

namespace App\Services\Order;

use App\Exceptions\BusinessError;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\App;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class PreferenceService extends BaseService
{
    private const PRICE_ZERO = 0;

    public function create($address, $cart): array
    {
        MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
        $client = new PreferenceClient();

        $externalReference = 'Reference_' . randomText(8);

        /* items */
        $items = [];
        foreach ($cart['products'] as $product) {
            $price = $product['newPrice'] ?? $product['price'];

            if ($price === self::PRICE_ZERO) {
                continue;
            }
            $items[] = [
                'id' => $product['id'], //required
                'title' => $product['title'], //required
                'quantity' => (int) $product['amount'], //required
                'unit_price' => $price, //required
                'currency_id' => 'BRL',
            ];
        }
        /* end items */

        /* payer */
        $payer = [
            'name' => $cart['user']->name,
            'surname' => $cart['user']->surname,
            'email' => $cart['user']->email,
            'date_created' => $cart['user']->created_at,
            'address' => [
                'zip_code' => $address->adr_zipcode,
                'street_name' => $address->adr_address,
                'street_number' => $address->adr_number,
            ],
        ];
        /* end payer */

        /* shipments */
        $freightData = $cart['freightData'];
        $freightPrice = (float) str_replace(',', '.', $freightData['price']);

        if (count($items) === 0) {
            $items[] = [
                'id' => 1, //required
                'title' => 'Envio: ' . $freightData['serviceName'], //required
                'quantity' => 1, //required
                'unit_price' => $freightPrice, //required
                'currency_id' => 'BRL',
            ];
        }
        $shipment = [
            'cost' => $freightPrice
        ];
        /* end shipments */

        try {
            $preferenceData = [
                'items' => $items,
                'payer' => $payer,
                'shipments' => $shipment,
                'back_urls' => [
                    'success' => route('site.payment.confirmation', ['success']),
                    'failure' => route('site.payment.confirmation', ['failure']),
                    'pending' => route('site.payment.confirmation', ['pending']),
                ],
                'auto_return' => 'approved',
                'notification_url' => env(
                    'MERCADO_PAGO_IPN',
                    route('api.notifications.ipn', ['source_news' => 'webhooks'])
                ),
                'external_reference' => $externalReference,

            ];

            $preference = $client->create($preferenceData);

            debug([
                'debug message' => 'Generate new preference for mercado pago checkout',
                'externalReference' => $externalReference,
                'preference' => json_encode($preference),
            ]);

            return [
                'id' => $preference->id,
                'external_reference' => $preference->external_reference,
                'init_point' => App::environment('local')
                    ? $preference->sandbox_init_point
                    : $preference->init_point,
            ];
        } catch (MPApiException $exc) {
            debug('Error mapped type MPApiException');
            debug($exc->getApiResponse()->getStatusCode());
            debug($exc->getApiResponse()->getContent());
            throw new BusinessError('Ocorreu um erro ao integrar com o mercado pago. Tente novamente mais tarde!');
        } catch (Exception $exc) {
            debug('Error not mapped for preference creation');
            debug($exc->getMessage());
            throw new BusinessError('Ocorreu um erro ao integrar com o mercado pago. Tente novamente mais tarde!');
        }
    }
}
