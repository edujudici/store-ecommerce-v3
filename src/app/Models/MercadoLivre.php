<?php

namespace App\Models;

class MercadoLivre extends BaseModel
{
    protected $table = 'mercadolivre';
    protected $primaryKey = 'mel_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mel_title',
        'mel_code_tg',
        'mel_access_token',
        'mel_token_type',
        'mel_expires_in',
        'mel_scope',
        'mel_user_id',
        'mel_refresh_token',
        'mel_after_sales_message',
        'mel_after_sales_enabled',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mel_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the comments for the mercado livre account.
     */
    public function comments()
    {
        return $this->hasMany(
            'App\Models\MercadoLivreComment',
            'mec_seller_id',
            'mel_user_id'
        );
    }

    /**
     * Get the notifications for the mercado livre account.
     */
    public function notifications()
    {
        return $this->hasMany(
            'App\Models\MercadoLivreNotification',
            'men_user_id',
            'mel_user_id'
        );
    }

    /**
     * Get the histories for the mercado livre account.
     */
    public function histories()
    {
        return $this->hasMany(
            'App\Models\LoadQuestionHistory',
            'lqh_account_id',
            'mel_id'
        );
    }
}
