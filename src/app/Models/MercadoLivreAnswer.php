<?php

namespace App\Models;

class MercadoLivreAnswer extends BaseModel
{
    protected $table = 'mercadolivre_answers';
    protected $primaryKey = 'mea_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mea_description',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mea_id',
        'created_at',
        'update_at',
    ];
}
