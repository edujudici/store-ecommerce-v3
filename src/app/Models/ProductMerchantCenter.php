<?php

namespace App\Models;

class ProductMerchantCenter extends BaseModel
{
    protected $table = 'products_merchantcenter';
    protected $primaryKey = 'prm_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'prm_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the merchant center.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'pro_sku', 'pro_sku');
    }
}
