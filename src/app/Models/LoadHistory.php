<?php

namespace App\Models;

class LoadHistory extends BaseModel
{
    protected $table = 'loads_history';
    protected $primaryKey = 'loh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loh_total',
        'loh_account_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'loh_id',
        'created_at',
        'update_at',
    ];
}
