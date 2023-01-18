<?php

namespace App\Models;

class OrderPayment extends BaseModel
{
    protected $table = 'orders_payment';
    protected $primaryKey = 'orp_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'orp_payment_id',
        'orp_order_id',
        'orp_payer_id',
        'orp_payer_email',
        'orp_payer_first_name',
        'orp_payer_last_name',
        'orp_payer_phone',
        'orp_payment_method_id',
        'orp_payment_type_id',
        'orp_status',
        'orp_status_detail',
        'orp_transaction_amount',
        'orp_received_amount',
        'orp_resource_url',
        'orp_total_paid_amount',
        'orp_shipping_amount',
        'orp_date_approved',
        'orp_date_created',
        'orp_date_of_expiration',
        'orp_live_mode',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'orp_id',
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
