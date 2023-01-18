<?php

namespace App\Models;

class OrderMerchant extends BaseModel
{
    protected $table = 'orders_merchant';
    protected $primaryKey = 'orm_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'orm_notification_id',
        'orm_notification_topic',
        'orm_order_status',
        'orm_paid_amount',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'orm_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the order that owns the item.
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Order', 'ord_id', 'ord_id');
    }
}
