<?php

namespace App\Models;

class Newsletter extends BaseModel
{
    protected $table = 'newsletters';
    protected $primaryKey = 'new_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'new_email',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'new_id',
        'created_at',
        'update_at',
    ];
}
