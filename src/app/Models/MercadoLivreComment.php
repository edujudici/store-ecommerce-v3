<?php

namespace App\Models;

class MercadoLivreComment extends BaseModel
{
    protected $table = 'mercadolivre_comments';
    protected $primaryKey = 'mec_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mec_date_created',
        'mec_item_id',
        'mec_seller_id',
        'mec_status',
        'mec_text',
        'mec_id_secondary',
        'mec_deleted_from_listing',
        'mec_hold',
        'mec_answer_local',
        'mec_answer_date',
        'mec_answer_status',
        'mec_answer_text',
        'mec_from_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'mec_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the mercadolivre that owns the mercadolivre comnents.
     */
    public function mercadolivre()
    {
        return $this->belongsTo(
            'App\Models\MercadoLivre',
            'mec_seller_id',
            'mel_user_id'
        );
    }

    /**
     * Get the user record associated with the mercadolivre comments.
     */
    public function user()
    {
        return $this->hasOne(
            'App\Models\MercadoLivreUser',
            'meu_user_id',
            'mec_from_id'
        );
    }

    /**
     * Get the product record associated with the mercadolivre comments.
     */
    public function product()
    {
        return $this->hasOne(
            'App\Models\MercadoLivreProduct',
            'mep_item_id',
            'mec_item_id'
        );
    }
}
