<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardNewShoppersService extends BaseService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): Collection
    {
        return $this->user
            ->where('role', 'shopper')
            ->limit(10)
            ->orderBy('id', 'desc')
            ->get();
    }
}
