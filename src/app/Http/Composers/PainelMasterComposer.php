<?php

namespace App\Http\Composers;

use App\Services\Painel\CompanyService;
use App\Traits\AuthApi;
use App\Traits\MakeRequest;
use Illuminate\View\View;

class PainelMasterComposer
{
    use MakeRequest, AuthApi;

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $tokenApi = $this->apiAuth();
        $company = $this->_callService(CompanyService::class, 'index', []);

        $notifications = auth()->user()->unreadNotifications;
        $view
            ->with('company', json_encode($company['response']))
            ->with('tokenApi', $tokenApi)
            ->with('notifications', $notifications);
    }
}
