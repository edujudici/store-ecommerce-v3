<?php

namespace App\Models;

class Voucher extends BaseModel
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_APPLIED = 'applied';

    protected $table = 'vouchers';
    protected $primaryKey = 'vou_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vou_id_base',
        'user_uuid',
        'vou_code',
        'vou_value',
        'vou_expiration_date',
        'vou_applied_date',
        'vou_description',
        'vou_status',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'vou_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the user that owns the voucher.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_uuid', 'uuid');
    }

    public static function getStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Ativo',
            self::STATUS_INACTIVE => 'Inativo',
        ];
    }
}
