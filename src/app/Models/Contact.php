<?php

namespace App\Models;

class Contact extends BaseModel
{
    protected $table = 'contacts';
    protected $primaryKey = 'con_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'con_name',
        'con_email',
        'con_subject',
        'con_message',
        'con_answer',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'con_id',
        'created_at',
        'update_at',
    ];
}
