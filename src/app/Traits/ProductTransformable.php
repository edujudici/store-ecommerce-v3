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
            'pro_title' => $data->title, //default
            'pro_description' => $data->title, //default
            'pro_price' => $data->price, //default
            'pro_oldprice' => $data->price, //default
            'pro_sku' => $data->id, //default
            'pro_category_id' => $data->category_id,
            'pro_seller_id' => $data->seller_id
                ?? $data->seller->id,
            'pro_condition' => $data->condition,
            'pro_permalink' => $data->permalink,
            'pro_secure_thumbnail' => $data->secure_thumbnail ?? null,
            'pro_accepts_merc_pago' => $data->accepts_mercadopago,
            'pro_load_date' => $loadDate,
            'pro_sold_quantity' => $data->sold_quantity ?? 0,
            'pro_external' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
