<?php

namespace App\Models;

class MercadoLivreUser extends BaseModel
{
    protected $table = 'mercadolivre_users';
    protected $primaryKey = 'meu_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meu_user_id',
        'meu_nickname',
        'meu_registration_date',
        'meu_address_city',
        'meu_address_state',
        'meu_points',
        'meu_permalink',
        'meu_level_id',
        'meu_power_seller_status',
        'meu_transactions_canceled',
        'meu_transactions_completed',
        'meu_transactions_total',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'meu_id',
        'created_at',
        'update_at',
    ];
}
