<?php

namespace App\Models;

class ProductMerchantCenterHistory extends BaseModel
{
    protected $table = 'products_merchantcenter_history';
    protected $primaryKey = 'pmh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pmh_total',
        'pmh_account_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'pmh_id',
        'created_at',
        'update_at',
    ];
}
