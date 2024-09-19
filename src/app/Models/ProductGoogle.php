<?php

namespace App\Models;

class ProductGoogle extends BaseModel
{
    protected $table = 'products_google';
    protected $primaryKey = 'pgo_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'pgo_product_id',
        'pgo_product_kind',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'pgo_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the product google.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'pro_sku', 'pro_sku');
    }
}
