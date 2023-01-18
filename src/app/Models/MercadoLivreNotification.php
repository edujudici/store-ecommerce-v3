<?php

namespace App\Models;

class MercadoLivreNotification extends BaseModel
{
    protected $table = 'mercadolivre_notifications';
    protected $primaryKey = 'men_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'men_resource',
        'men_user_id',
        'men_topic',
        'men_application_id',
        'men_attempts',
        'men_sent',
        'men_received',
        'men_send_message',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'men_id',
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
            'men_user_id',
            'mel_user_id'
        );
    }
}
