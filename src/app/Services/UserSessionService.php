<?php

namespace App\Services;

use App\Models\UserSession;

class UserSessionService extends BaseService
{
    private const USER_SESSION_TYPE = 'favorite';

    private $userSession;

    public function __construct(UserSession $userSession)
    {
        $this->userSession = $userSession;
    }

    public function findByUser($userId): ?UserSession
    {
        return $this->userSession
            ->where('user_id', $userId)
            ->where('uss_type', self::USER_SESSION_TYPE)
            ->first();
    }

    public function store($user, $json): UserSession
    {
        return $user->favorite()->updateOrCreate([
            'user_id' => $user->id,
            'uss_type' => self::USER_SESSION_TYPE,
        ], [
            'uss_json' => $json,
        ]);
    }
}
