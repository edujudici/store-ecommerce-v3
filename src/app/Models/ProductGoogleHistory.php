<?php

namespace App\Models;

class ProductGoogleHistory extends BaseModel
{
    protected $table = 'products_google_history';
    protected $primaryKey = 'pgh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pgh_total',
        'pgh_account_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'pgh_id',
        'created_at',
        'update_at',
    ];
}
