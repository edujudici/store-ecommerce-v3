<?php

namespace App\Models;

class Brand extends BaseModel
{
    protected $table = 'brands';
    protected $primaryKey = 'bra_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bra_image',
        'bra_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'bra_id',
        'created_at',
        'update_at',
    ];
}
