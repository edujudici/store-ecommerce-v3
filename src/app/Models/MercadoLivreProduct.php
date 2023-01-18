<?php

namespace App\Models;

class MercadoLivreProduct extends BaseModel
{
    protected $table = 'mercadolivre_products';
    protected $primaryKey = 'mep_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mep_item_id',
        'mep_title',
        'mep_price',
        'mep_permalink',
        'mep_secure_thumbnail',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mep_id',
        'created_at',
        'update_at',
    ];
}
