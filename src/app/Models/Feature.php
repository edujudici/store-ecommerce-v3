<?php

namespace App\Models;

class Feature extends BaseModel
{
    protected $table = 'features';
    protected $primaryKey = 'fea_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fea_title',
        'fea_description',
        'fea_image',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'fea_id',
        'created_at',
        'update_at',
    ];
}
