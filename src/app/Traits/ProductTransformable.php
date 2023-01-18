<?php

namespace App\Traits;

trait ProductTransformable
{
    /**
     * Transform the product
     *
     * @param Object $data
     * @param array $product
     *
     * @return array
     */
    protected static function prepareProduct($data, $loadDate): array
    {
        return [
            'pro_title' => $data->title,
            'pro_description' => $data->title,
            'pro_price' => $data->price,
            'pro_oldprice' => $data->price,
            'pro_sku' => $data->id,
            'pro_category_id' => $data->category_id,
            'pro_condition' => $data->condition,
            'pro_permalink' => $data->permalink,
            'pro_accepts_merc_pago' => $data->accepts_mercadopago,
            'pro_sold_quantity' => $data->sold_quantity,
            'pro_load_date' => $loadDate,
            'pro_seller_id' => $data->seller_id
                ?? $data->seller->id,
            'pro_secure_thumbnail' => $data->secure_thumbnail ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'pro_external' => true,
        ];
    }
}
