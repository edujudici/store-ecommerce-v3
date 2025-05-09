<?php

namespace App\Providers;

use App\Http\Composers\MasterComposer;
use App\Http\Composers\PainelMasterComposer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        view()->composer('site.baseTemplate', MasterComposer::class);
        view()->composer('painel.baseTemplate', PainelMasterComposer::class);
    }
}
