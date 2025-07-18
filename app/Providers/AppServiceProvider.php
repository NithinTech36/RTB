<?php

namespace App\Providers;

use App\Events\WinnerEvent;
use App\Listeners\WinnerEventNotification;
use Event;
use Illuminate\Support\ServiceProvider; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
          //Register any event
        // You can register events here if needed
        Event::listen(WinnerEvent::class,WinnerEventNotification::class); 
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
