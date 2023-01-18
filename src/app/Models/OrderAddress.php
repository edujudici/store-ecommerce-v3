<?php

namespace App\Models;

class OrderAddress extends BaseModel
{
    protected $table = 'orders_address';
    protected $primaryKey = 'ora_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'ora_name',
        'ora_surname',
        'ora_phone',
        'ora_zipcode',
        'ora_address',
        'ora_number',
        'ora_district',
        'ora_city',
        'ora_complement',
        'ora_type',
        'ora_uf',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'ora_id',
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
