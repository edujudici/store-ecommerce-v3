<?php

namespace App\Models;

class MerchantCenter extends BaseModel
{
    protected $table = 'merchantcenter';
    protected $primaryKey = 'mec_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mec_token_type',
        'mec_expires_in',
        'mec_access_token',
        'mec_refresh_token',
        'mec_created',
        'mec_scope'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mec_id',
        'created_at',
        'update_at',
    ];
}
