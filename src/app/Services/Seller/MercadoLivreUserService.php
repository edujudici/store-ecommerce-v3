<?php

namespace App\Services\Seller;

use App\Models\MercadoLivreUser;
use App\Services\BaseService;

class MercadoLivreUserService extends BaseService
{
    private $mercadoLivreUser;

    public function __construct(MercadoLivreUser $mercadoLivreUser)
    {
        $this->mercadoLivreUser = $mercadoLivreUser;
    }

    public function exists($userId)
    {
        return $this->mercadoLivreUser
            ->where('meu_user_id', $userId)
            ->exists();
    }

    public function store($comment, $user): void
    {
        $comment->user()->create($this->prepareUser($user));
    }

    private function prepareUser($user): array
    {
        $rep = $user->seller_reputation;
        return [
            'meu_user_id' => $user->id,
            'meu_nickname' => $user->nickname,
            'meu_registration_date' => date(
                'Y-m-d H:i:s',
                strtotime($user->registration_date)
            ),
            'meu_address_city' => $user->address ? $user->address->city : null,
            'meu_address_state' => $user->address ? $user->address->state : null,
            'meu_points' => $user->points,
            'meu_permalink' => $user->permalink,
            'meu_level_id' => $rep->level_id,
            'meu_power_seller_status' => $rep->power_seller_status,
            'meu_transactions_canceled' => $rep->transactions->canceled,
            'meu_transactions_completed' => $rep->transactions->completed,
            'meu_transactions_total' => $rep->transactions->total,
        ];
    }
}
