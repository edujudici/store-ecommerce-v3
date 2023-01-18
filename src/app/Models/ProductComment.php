<?php

namespace App\Models;

class ProductComment extends BaseModel
{
    protected $table = 'products_comment';
    protected $primaryKey = 'prc_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pro_sku',
        'prc_name',
        'prc_question',
        'prc_answer',
        'prc_answer_date',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'prc_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the product that owns the comments.
     */
    public function product()
    {
        return $this->belongsTo(
            'App\Models\Product',
            'pro_sku',
            'pro_sku'
        );
    }
}
