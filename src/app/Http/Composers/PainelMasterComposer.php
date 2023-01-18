<?php

namespace App\Http\Composers;

use App\Traits\MakeRequest;
use Illuminate\View\View;

class PainelMasterComposer
{
    use MakeRequest;

    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $notifications = auth()->user()->unreadNotifications;
        $view
            ->with('notifications', $notifications);
    }
}
