<?php

namespace App\Models;

class OrderHistory extends BaseModel
{
    protected $table = 'orders_history';
    protected $primaryKey = 'orh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'orh_preference_id',
        'orh_collection_status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'orh_id',
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
}
