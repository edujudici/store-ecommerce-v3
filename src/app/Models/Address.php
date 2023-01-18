<?php

namespace App\Models;

class Address extends BaseModel
{
    protected $table = 'addresses';
    protected $primaryKey = 'adr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'adr_name',
        'adr_surname',
        'adr_phone',
        'adr_zipcode',
        'adr_address',
        'adr_number',
        'adr_district',
        'adr_city',
        'adr_complement',
        'adr_type',
        'adr_uf',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'adr_id',
        'created_at',
        'update_at',
    ];
}
