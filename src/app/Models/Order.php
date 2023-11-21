<?php

namespace App\Models;

class Order extends BaseModel
{
    public const STATUS_NEW = 'new';
    public const STATUS_PAYMENT_IN_PROCESS = 'payment_in_process';
    public const STATUS_PAID = 'paid';
    public const STATUS_PRODUCTION = 'production';
    public const STATUS_TRANSPORT = 'transport';
    public const STATUS_COMPLETE = 'complete';
    public const STATUS_CANCEL = 'cancel';

    protected $table = 'orders';
    protected $primaryKey = 'ord_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ord_protocol',
        'ord_preference_id',
        'ord_preference_init_point',
        'ord_external_reference',
        'ord_subtotal',
        'ord_freight_code',
        'ord_freight_service',
        'ord_freight_time',
        'ord_freight_price',
        'ord_total',
        'ord_delivery_date',
        'ord_voucher_code',
        'ord_voucher_value',
        'ord_promised_date',
        'ord_promised_date_recalculated',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'ord_id',
        'created_at',
        'update_at',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany('App\Models\OrderItem', 'ord_id', 'ord_id');
    }

    /**
     * Get the histories for the order.
     */
    public function histories()
    {
        return $this->hasMany('App\Models\OrderHistory', 'ord_id', 'ord_id')
            ->orderBy('created_at');
    }

    /**
     * Get the addresses for the order.
     */
    public function address()
    {
        return $this->hasOne('App\Models\OrderAddress', 'ord_id', 'ord_id');
    }

    /**
     * Get the comments for the order.
     */
    public function comments()
    {
        return $this->hasMany('App\Models\OrderComment', 'ord_id', 'ord_id');
    }

    /**
     * Get the payments for the order.
     */
    public function payment()
    {
        return $this->hasOne('App\Models\OrderPayment', 'ord_id', 'ord_id');
    }

    public static function getStatus(): array
    {
        return [
            self::STATUS_PRODUCTION => 'Em produção',
            self::STATUS_TRANSPORT => 'Em transporte',
            self::STATUS_COMPLETE => 'Concluído',
            self::STATUS_CANCEL => 'Cancelar',
        ];
    }

    public static function getAllStatus(): array
    {
        return [
            self::STATUS_NEW => 'Novo',
            self::STATUS_PAYMENT_IN_PROCESS => 'Processando Pagamento',
            self::STATUS_PAID => 'Pago',
            self::STATUS_PRODUCTION => 'Em produção',
            self::STATUS_TRANSPORT => 'Em transporte',
            self::STATUS_COMPLETE => 'Concluído',
            self::STATUS_CANCEL => 'Cancelado',
        ];
    }

    public static function getStatusDescription($status): string
    {
        $allStatus = self::getAllStatus();
        return $allStatus[$status];
    }
}
