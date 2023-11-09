<?php

namespace App\Services\Payment;

use App\Exceptions\BusinessError;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\BaseService;
use App\Services\Order\OrderService;
use MercadoPago\MerchantOrder;
use MercadoPago\Payment;
use MercadoPago\SDK;

class PayService extends BaseService
{
    private const STATUS_APPROVED = 'approved';

    public $merchantOrder;
    public $payment;
    private $orderService;
    private $orderPayment;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(
        OrderService $orderService,
        OrderPayment $orderPayment
    ) {
        $this->orderService = $orderService;
        $this->orderPayment = $orderPayment;
    }

    /**
     * Notification, called when change payment
     *
     * @param array $request
     *
     * @return void
     */
    public function processNotification($params): void
    {
        debug(['process notifications with params' => $params]);

        SDK::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        $this->intanceClass();

        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'payment':
                    $payment = $this->payment::find_by_id($params['data']['id']);
                    if (is_null($payment)) {
                        throw new BusinessError('Payment not found');
                    }
                    $merchantOrder = $this->merchantOrder::find_by_id(
                        $payment->order->id
                    );
                    if (is_null($merchantOrder)) {
                        throw new BusinessError('Merchant order not found');
                    }
                    $this->storePaymentOrder($payment, $merchantOrder);
                    break;
                default:
                    throw new BusinessError('Notification type does not exists');
            }
        }
    }

    /**
     * Instance class with depency on SDK
     *
     * @return void
     */
    private function intanceClass(): void
    {
        if (is_null($this->merchantOrder)) {
            $this->merchantOrder = new MerchantOrder();
        }
        if (is_null($this->payment)) {
            $this->payment = new Payment();
        }
    }

    /**
     * Save payment data
     *
     * @param \MercadoPago\Payment $payment
     * @param \MercadoPago\Merchant $payment
     *
     * @return void
     */
    private function storePaymentOrder(
        Payment $payment,
        MerchantOrder $merchantOrder
    ): void {
        $order = $this->orderService->store(null, [
            'preferenceId' => $merchantOrder->preference_id,
            'status' => $payment->status === self::STATUS_APPROVED
                ? Order::STATUS_PAID
                : Order::STATUS_PAYMENT_IN_PROCESS,
            'approvedDate' => $payment->date_approved,
        ]);

        $this->orderPayment->create([
            'ord_id' => $order->ord_id,
            'orp_payment_id' => $payment->id,
            'orp_order_id' => $payment->order->id,
            'orp_payer_id' => $payment->payer->id,
            'orp_payer_email' => $payment->payer->email,
            'orp_payer_first_name' => $payment->payer->first_name,
            'orp_payer_last_name' => $payment->payer->last_name,
            'orp_payer_phone' => $payment->payer->phone->number
                ?? null,
            'orp_payment_method_id' => $payment->payment_method_id,
            'orp_payment_type_id' => $payment->payment_type_id,
            'orp_status' => $payment->status,
            'orp_status_detail' => $payment->status_detail,
            'orp_transaction_amount' => $payment->transaction_amount,
            'orp_received_amount' => $payment
                ->transaction_details->net_received_amount,
            'orp_resource_url' => $payment
                ->transaction_details->external_resource_url,
            'orp_total_paid_amount' => $payment
                ->transaction_details->total_paid_amount,
            'orp_shipping_amount' => $payment->shipping_amount,
            'orp_date_approved' => ! empty($payment->date_approved)
                ? date('Y-m-d H:i:s', strtotime($payment->date_approved))
                : null,
            'orp_date_created' => ! empty($payment->date_created)
                ? date('Y-m-d H:i:s', strtotime($payment->date_created))
                : null,
            'orp_date_of_expiration' => ! empty($payment->date_of_expiration)
                ? date('Y-m-d H:i:s', strtotime($payment->date_of_expiration))
                : null,
            'orp_live_mode' => $payment->live_mode,
        ]);
    }
}
