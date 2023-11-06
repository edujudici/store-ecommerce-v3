<?php

namespace App\Traits;

trait FreightTransformable
{
    /**
     * Transform the freight
     *
     * @param array $data
     * @param array $freight
     *
     * @return array
     */
    protected static function prepareFreight(array $data): array
    {
        return [
            'id' => isset($data['id']) ? $data['id'] : null,
            'name' => isset($data['name']) ? $data['name'] : null,
            'price' => isset($data['custom_price']) ? $data['custom_price'] : null,
            'deliveryTime' => isset($data['custom_delivery_time']) ? $data['custom_delivery_time'] : null,
            'deliveryRange' => [
                'min' => isset($data['custom_delivery_range']['min'])
                    ? $data['custom_delivery_range']['min'] : null,
                'max' => isset($data['custom_delivery_range']['max'])
                    ? $data['custom_delivery_range']['max'] : null
            ],
            'package' => [
                'price' => isset($data['packages'][0]['price'])
                    ? $data['packages'][0]['price'] : null,
                'discount' => isset($data['packages'][0]['discount'])
                    ? $data['packages'][0]['discount'] : null,
                'format' => isset($data['packages'][0]['format'])
                    ? $data['packages'][0]['format'] : null,
                'dimensions' => [
                    'height' => isset($data['packages'][0]['dimensions']['height'])
                        ? $data['packages'][0]['dimensions']['height'] : null,
                    'width' => isset($data['packages'][0]['dimensions']['width'])
                        ? $data['packages'][0]['dimensions']['width'] : null,
                    'length' => isset($data['packages'][0]['dimensions']['length'])
                        ? $data['packages'][0]['dimensions']['length'] : null
                ],
                'weight' => isset($data['packages'][0]['weight'])
                    ? $data['packages'][0]['weight'] : null,
                'insuranceValue' => isset($data['packages'][0]['insurance_value'])
                    ? $data['packages'][0]['insurance_value'] : null
            ],
            'company' => [
                'id' => isset($data['company']['id'])
                    ? $data['company']['id'] : null,
                'name' => isset($data['company']['name'])
                    ? $data['company']['name'] : null,
                'picture' => isset($data['company']['picture'])
                    ? $data['company']['picture'] : null
            ],
        ];
    }
}
