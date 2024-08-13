<?php

namespace App\Models;

class ProductRelated extends BaseModel
{
    protected $table = 'products_related';
    protected $primaryKey = 'prr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'pro_sku_related',
        'prr_external',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'prr_id',
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
