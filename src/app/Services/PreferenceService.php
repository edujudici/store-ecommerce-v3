<?php

namespace App\Services;

use App;
use App\Exceptions\BusinessError;
use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Preference;
use MercadoPago\SDK;
use MercadoPago\Shipments;

class PreferenceService extends BaseService
{
    private const PRICE_ZERO = 0;

    private $preference;

    public function __construct()
    {
        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));
        $this->preference = new Preference();
    }

    public function create($address, $cart): array
    {
        // if (! App::environment('testing')) {
        //     if ($cart['user']->email === 'client@client.com'
        //         || $cart['user']->email == 'shopper@shopper.com') {
        //         debug('valid e-mail to homolog');
        //         $cart['user']->email = 'test_user_36370294@testuser.com';
        //         $cart['freightData']['price'] = '0,00';
        //         $cart['products'] = array_map(static function ($item) {
        //             $item['price'] = 0.5;
        //             return $item;
        //         }, $cart['products']);
        //     }
        // }

        $this->loadItems($cart['products']);
        $this->loadPayer($cart['user'], $address);
        $this->loadShipment($cart['freightData']);
        $this->preference->back_urls = [
            'success' => route('site.payment.confirmation', ['success']),
            'failure' => route('site.payment.confirmation', ['failure']),
            'pending' => route('site.payment.confirmation', ['pending']),
        ];
        $this->preference->auto_return = 'approved';
        $this->preference->notification_url = env(
            'MERCADO_PAGO_IPN',
            route('api.notifications.ipn', ['source_news' => 'webhooks'])
        );
        $externalReference = 'Reference_' . randomText(8);
        $this->preference->external_reference = $externalReference;
        $this->preference->save();

        if (is_null($this->preference->id)) {
            throw new BusinessError('Ocorreu um erro ao integrar com o
            mercado pago. Tente novamente mais tarde!');
        }

        return [
            'id' => $this->preference->id,
            'external_reference' => $externalReference,
            'init_point' => App::environment('local')
                ? $this->preference->sandbox_init_point
                : $this->preference->init_point,
        ];
    }

    private function loadItems($products): void
    {
        $items = [];
        foreach ($products as $product) {
            $price = $product['newPrice']
                ?? $product['price'];
            if ($price === self::PRICE_ZERO) {
                continue;
            }
            $item = new Item();
            $item->id = $product['id'];
            $item->title = $product['title'];
            $item->quantity = $product['amount'];
            $item->currency_id = 'BRL';
            $item->unit_price = $price;
            $items[] = $item;
        }
        $this->preference->items = $items;
    }

    private function loadPayer($user, $address): void
    {
        $payer = new Payer();
        $payer->name = $user->name;
        $payer->surname = $user->surname;
        $payer->email = $user->email;
        $payer->date_created = $user->created_at;
        $payer->address = [
            'street_name' => $address->adr_address,
            'street_number' => $address->adr_number,
            'zip_code' => $address->adr_zipcode,
        ];
        $this->preference->payer = $payer;
    }

    private function loadShipment($freightData): void
    {
        $freightPrice = (float) str_replace(',', '.', $freightData['price']);

        if (count($this->preference->items) === 0) {
            $item = new Item();
            $item->title = 'Envio: ' . $freightData['serviceName'];
            $item->quantity = 1;
            $item->currency_id = 'BRL';
            $item->unit_price = $freightPrice;

            $this->preference->items = [$item];
            return;
        }

        $shipment = new Shipments();
        $shipment->cost = $freightPrice;
        $this->preference->shipments = $shipment;
    }
}
