<?php

namespace App\Models;

class Picture extends BaseModel
{
    protected $table = 'pictures';
    protected $primaryKey = 'pic_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'pic_id_secondary',
        'pic_image',
        'pic_url',
        'pic_secure_url',
        'pic_size',
        'pic_max_size',
        'pic_quality',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'pic_id',
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
