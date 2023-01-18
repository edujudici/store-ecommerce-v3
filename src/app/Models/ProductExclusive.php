<?php

namespace App\Models;

class ProductExclusive extends BaseModel
{
    protected $table = 'products_exclusive';
    protected $primaryKey = 'pre_id';

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
        'pre_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the exclusive deal.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'pro_sku', 'pro_sku');
    }
}
