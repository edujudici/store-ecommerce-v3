<?php

namespace App\Models;

class LoadQuestionHistory extends BaseModel
{
    protected $table = 'loads_questions_history';
    protected $primaryKey = 'lqh_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lqh_total',
        'lqh_total_sync',
        'lqh_account_id',
        'lqh_account_title',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'lqh_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the mercadolivre that owns the notification.
     */
    public function mercadolivre()
    {
        return $this->belongsTo(
            'App\Models\MercadoLivre',
            'lqh_account_id',
            'mel_id'
        );
    }
}
