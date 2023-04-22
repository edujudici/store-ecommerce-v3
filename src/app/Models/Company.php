<?php

namespace App\Models;

class Company extends BaseModel
{
    protected $table = 'company';
    protected $primaryKey = 'com_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'com_title',
        'com_description',
        'com_image',
        'com_phone',
        'com_work_hours',
        'com_mail',
        'com_iframe',
        'com_address',
        'com_zipcode',
        'com_number',
        'com_district',
        'com_city',
        'com_complement',
        'com_uf',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'com_id',
        'created_at',
        'update_at',
    ];
}
