<?php

namespace App\Models;

class Google extends BaseModel
{
    protected $table = 'google';
    protected $primaryKey = 'goo_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'goo_token_type',
        'goo_expires_in',
        'goo_access_token',
        'goo_refresh_token',
        'goo_created',
        'goo_scope'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'goo_id',
        'created_at',
        'update_at',
    ];
}
