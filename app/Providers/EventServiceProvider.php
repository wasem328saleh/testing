<?php

namespace App\Providers;

use App\Events\CreateEvent;
use App\Events\DeleteAllEvent;
use App\Events\DeleteEvent;
use App\Events\UpdateEvent;
use App\Listeners\CreateListener;
use App\Listeners\DeleteAllListener;
use App\Listeners\DeleteListener;
use App\Listeners\UpdateListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreateEvent::class=>[CreateListener::class],
        DeleteAllEvent::class=>[DeleteAllListener::class],
        DeleteEvent::class=>[DeleteListener::class],
        UpdateEvent::class=>[UpdateListener::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
