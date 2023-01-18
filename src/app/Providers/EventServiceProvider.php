<?php

namespace App\Providers;

use App\Events\ContactRegistered;
use App\Events\NewsletterRegistered;
use App\Events\OrderCommentAnswerRegistered;
use App\Events\OrderCommentRegistered;
use App\Events\OrderPaidRegistered;
use App\Events\OrderRegistered;
use App\Events\OrderStatusRegistered;
use App\Events\ProductCommentRegistered;
use App\Listeners\SendNewContactNotification;
use App\Listeners\SendNewNewsletterNotification;
use App\Listeners\SendNewOrderCommentAnswerNotification;
use App\Listeners\SendNewOrderCommentNotification;
use App\Listeners\SendNewOrderNotification;
use App\Listeners\SendNewOrderPaidNotification;
use App\Listeners\SendNewOrderStatusNotification;
use App\Listeners\SendNewProductCommentNotification;
use App\Listeners\SendNewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            SendNewUserNotification::class,
        ],
        ContactRegistered::class => [
            SendNewContactNotification::class,
        ],
        OrderCommentRegistered::class => [
            SendNewOrderCommentNotification::class,
        ],
        OrderCommentAnswerRegistered::class => [
            SendNewOrderCommentAnswerNotification::class,
        ],
        ProductCommentRegistered::class => [
            SendNewProductCommentNotification::class,
        ],
        NewsletterRegistered::class => [
            SendNewNewsletterNotification::class,
        ],
        OrderRegistered::class => [
            SendNewOrderNotification::class,
        ],
        OrderPaidRegistered::class => [
            SendNewOrderPaidNotification::class,
        ],
        OrderStatusRegistered::class => [
            SendNewOrderStatusNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
