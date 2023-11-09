<?php

namespace App\Services\Seller;

use App\Models\MercadoLivreNotification;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;

class MercadoLivreNotificationService extends BaseService
{
    public const MESSAGE_ENABLED = 1;
    public const MESSAGE_NOT_SEND = 0;
    public const TOPIC_PAYMENTS = 'payments';

    private $mercadoLivreNotification;

    public function __construct(MercadoLivreNotification $mercadoLivreNotification)
    {
        $this->mercadoLivreNotification = $mercadoLivreNotification;
    }

    public function findByUser(): Collection
    {
        return $this->mercadoLivreNotification
            ->with('mercadolivre')
            ->where('men_topic', self::TOPIC_PAYMENTS)
            ->where('men_send_message', self::MESSAGE_NOT_SEND)
            ->whereHas('mercadolivre', function ($query) {
                $query->where('mel_after_sales_enabled', self::MESSAGE_ENABLED);
            })->get();
    }

    public function store($request): void
    {
        $this->mercadoLivreNotification->updateOrCreate([
            'men_resource' => $request->input('resource'),
        ], $this->prepareUser($request));
    }

    private function prepareUser($request): array
    {
        return [
            'men_resource' => $request->input('resource'),
            'men_user_id' => $request->input('user_id'),
            'men_topic' => $request->input('topic'),
            'men_application_id' => $request->input('application_id'),
            'men_attempts' => $request->input('attempts'),
            'men_sent' => date(
                'Y-m-d H:i:s',
                strtotime($request->input('sent'))
            ),
            'men_received' => date(
                'Y-m-d H:i:s',
                strtotime($request->input('received'))
            ),
        ];
    }
}
