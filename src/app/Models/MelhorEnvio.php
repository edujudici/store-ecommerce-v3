<?php

namespace App\Models;

class MelhorEnvio extends BaseModel
{
    protected $table = 'melhorenvio';
    protected $primaryKey = 'mee_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mee_token_type',
        'mee_expires_in',
        'mee_access_token',
        'mee_refresh_token',
        'mee_authorize_code',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mee_id',
        'created_at',
        'update_at',
    ];
}
