<?php

namespace App\Services\Seller;

use App\Api\ApiMercadoLibre;
use App\Jobs\AfterSalesMessage;
use App\Services\BaseService;

class AfterSalesMessageService extends BaseService
{
    public const NOME_COMPRADOR = '[NomeComprador]';
    public const PAYMENT_STATUS = 'approved';

    private $apiMercadoLibre;
    private $mlNotificationService;

    public function __construct(
        ApiMercadoLibre $apiMercadoLibre,
        MercadoLivreNotificationService $mlNotificationService
    ) {
        $this->apiMercadoLibre = $apiMercadoLibre;
        $this->mlNotificationService = $mlNotificationService;
    }

    public function send(): void
    {
        debug('Requested via command aftersalesmessage:send');
        $notifications = $this->mlNotificationService->findByUser();
        $notifications->each(function ($item) {
            $this->dispatch($item);
        });
    }

    public function dispatch($notification)
    {
        debug('dispatch after sales message to id: ' . $notification->men_id);
        AfterSalesMessage::dispatch($notification)
            ->onQueue('aftersales-message');

        $notification->men_send_message = true;
        $notification->save();
    }

    public function sendMessage($notification)
    {
        debug('Executing of the job AfterSalesMessage');
        $mlAccount = $notification->mercadolivre;
        $payment = $this->apiMercadoLibre
            ->getNotificationResource($mlAccount, $notification->men_resource);
        debug('send after sales message by ml account ' . $mlAccount);

        if ($payment->status === self::PAYMENT_STATUS) {
            debug('send message for order id: ' . $payment->order_id);
            $user = $this->apiMercadoLibre->getUserDetails($payment->payer->id);
            $text = str_replace(
                self::NOME_COMPRADOR,
                $user->nickname,
                $mlAccount->mel_after_sales_message
            );
            $this->apiMercadoLibre->afterSalesMessage(
                $mlAccount,
                $payment->order_id,
                $user->id,
                $text
            );
        }
    }
}
