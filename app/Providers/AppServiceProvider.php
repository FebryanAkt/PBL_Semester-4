<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\TransactionSuccess;
use App\Listeners\SendNotificationToSeller;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    protected $listen = [
    // ... event lain jika ada
    \App\Events\TransactionSuccess::class => [
        \App\Listeners\SendNotificationToSeller::class,
    ],
];

}
