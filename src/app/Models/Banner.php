<?php

namespace App\Models;

class Banner extends BaseModel
{
    protected $table = 'banners';
    protected $primaryKey = 'ban_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ban_title',
        'ban_description',
        'ban_image',
        'ban_url',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'ban_id',
        'created_at',
        'update_at',
    ];
}
