<?php

namespace App\Models;

class ProductSpecification extends BaseModel
{
    protected $table = 'products_specification';
    protected $primaryKey = 'prs_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'prs_key',
        'prs_value',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'prs_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the specification.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'pro_sku', 'pro_sku');
    }
}
