<?php

namespace App\Models;

class UserSession extends BaseModel
{
    protected $table = 'users_session';
    protected $primaryKey = 'uss_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uss_type',
        'uss_json',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'uss_id',
        'created_at',
        'update_at',
    ];
}
