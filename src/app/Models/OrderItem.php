<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $table = 'orders_item';
    protected $primaryKey = 'ori_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'ori_pro_id',
        'ori_pro_sku',
        'ori_amount',
        'ori_price',
        'ori_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'ori_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo(
            'App\Models\Order',
            'ord_id',
            'ord_id'
        );
    }

    /**
     * Get the products for the order.
     */
    public function product()
    {
        return $this->hasOne('App\Models\Product', 'pro_sku', 'ori_pro_sku')
            ->withTrashed();
    }
}
