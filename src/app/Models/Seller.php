<?php

namespace App\Models;

class Seller extends BaseModel
{
    protected $table = 'sellers';
    protected $primaryKey = 'sel_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sel_nickname',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'sel_id',
        'created_at',
        'update_at',
    ];
}
