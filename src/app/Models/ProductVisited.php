<?php

namespace App\Models;

class ProductVisited extends BaseModel
{
    protected $table = 'products_visited';
    protected $primaryKey = 'prv_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'prv_visited',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'prv_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the related product.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'pro_sku', 'pro_sku');
    }
}
