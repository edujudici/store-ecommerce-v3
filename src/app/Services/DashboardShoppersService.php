<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardShoppersService extends BaseService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(): Collection
    {
        return $this->user->selectRaw('
            count(id) as total,
            YEAR(created_at) year,
            MONTH(created_at) month
        ')
            // ->whereRaw('YEAR(created_at) = YEAR(NOW())')
            ->groupBy('year', 'month')
            ->get();
    }
}
