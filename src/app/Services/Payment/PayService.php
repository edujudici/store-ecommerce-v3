<?php

namespace App\Services\Payment;

use App\Exceptions\BusinessError;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\BaseService;
use App\Services\Order\OrderService;
use MercadoPago\MercadoPagoConfig;

class PayService extends BaseService
{
    private const STATUS_APPROVED = 'approved';
    private $orderService;
    private $orderPayment;
    private $payClientInterface;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct(
        OrderService $orderService,
        OrderPayment $orderPayment,
        PayClientInterface $payClientInterface
    ) {
        $this->orderService = $orderService;
        $this->orderPayment = $orderPayment;
        $this->payClientInterface = $payClientInterface;
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
        debug(['Executing of the job PayNotification with params' => $params]);

        MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));

        if (isset($params['type'])) {
            switch ($params['type']) {
                case 'payment':
                    // payment
                    $payment = $this->payClientInterface->getPaymentClient()->get($params['data']['id']);
                    if (is_null($payment) || $payment->getResponse() === null) {
                        throw new BusinessError('Payment not found');
                    }
                    if ($payment->getResponse()->getStatusCode() !== 200) {
                        throw new BusinessError('Payment error: ' . $payment->getResponse()->getStatusCode());
                    }
                    $order = $payment->getResponse()->getContent()['order'];
                    if (!isset($order['id'])) {
                        throw new BusinessError('Payment order not found');
                    }

                    // merchant order
                    $merchantOrder = $this->payClientInterface->getMerchantOrderClient()->get($order['id']);
                    if (is_null($merchantOrder) || $merchantOrder->getResponse() === null) {
                        throw new BusinessError('Merchant order not found');
                    }
                    if ($merchantOrder->getResponse()->getStatusCode() !== 200) {
                        throw new BusinessError('Merchant error: ' . $merchantOrder->getResponse()->getStatusCode());
                    }
                    $this->storePaymentOrder(
                        $payment->getResponse()->getContent(),
                        $merchantOrder->getResponse()->getContent()
                    );

                    debug('Executing of the job PayNotification succssesfully done');
                    break;
                default:
                    throw new BusinessError('Notification type does not exists');
            }
        }
    }

    /**
     * Save payment data
     *
     * @param array $payment
     * @param array $payment
     *
     * @return void
     */
    private function storePaymentOrder(
        array $payment,
        array $merchantOrder
    ): void {
        $order = $this->orderService->store(null, [
            'preferenceId' => $merchantOrder['preference_id'],
            'status' => $payment['status'] === self::STATUS_APPROVED
                ? Order::STATUS_PAID
                : Order::STATUS_PAYMENT_IN_PROCESS,
            'approvedDate' => $payment['date_approved'],
        ]);

        $this->orderPayment->create([
            'ord_id' => $order->ord_id,
            'orp_payment_id' => $payment['id'],
            'orp_order_id' => $payment['order']['id'],
            'orp_payer_id' => $payment['payer']['id'],
            'orp_payer_email' => $payment['payer']['email'],
            'orp_payer_first_name' => $payment['payer']['first_name'],
            'orp_payer_last_name' => $payment['payer']['last_name'],
            'orp_payer_phone' => $payment['payer']['phone']['number']
                ?? null,
            'orp_payment_method_id' => $payment['payment_method_id'],
            'orp_payment_type_id' => $payment['payment_type_id'],
            'orp_status' => $payment['status'],
            'orp_status_detail' => $payment['status_detail'],
            'orp_transaction_amount' => $payment['transaction_amount'],
            'orp_received_amount' => $payment['transaction_details']['net_received_amount'],
            'orp_resource_url' => $payment['transaction_details']['external_resource_url'],
            'orp_total_paid_amount' => $payment['transaction_details']['total_paid_amount'],
            'orp_shipping_amount' => $payment['shipping_amount'],
            'orp_date_approved' => !empty($payment['date_approved'])
                ? date('Y-m-d H:i:s', strtotime($payment['date_approved']))
                : null,
            'orp_date_created' => !empty($payment['date_created'])
                ? date('Y-m-d H:i:s', strtotime($payment['date_created']))
                : null,
            'orp_date_of_expiration' => !empty($payment['date_of_expiration'])
                ? date('Y-m-d H:i:s', strtotime($payment['date_of_expiration']))
                : null,
            'orp_live_mode' => $payment['live_mode'],
        ]);
    }
}
