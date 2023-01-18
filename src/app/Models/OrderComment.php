<?php

namespace App\Models;

class OrderComment extends BaseModel
{
    protected $table = 'orders_comment';
    protected $primaryKey = 'orc_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ord_id',
        'orc_name',
        'orc_question',
        'orc_answer',
        'orc_answer_date',
        'orc_image',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'orc_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the order that owns the comments.
     */
    public function order()
    {
        return $this->belongsTo(
            'App\Models\Order',
            'ord_id',
            'ord_id'
        );
    }
}
