<?php

namespace App\Models;

class Faq extends BaseModel
{
    protected $table = 'faqs';
    protected $primaryKey = 'faq_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'faq_title',
        'faq_description',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'faq_id',
        'created_at',
        'update_at',
    ];
}
