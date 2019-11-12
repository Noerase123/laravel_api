<?php

namespace App\Providers;

use App\Events;
use App\Listeners;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Events\Api\User\UserCreated::class => [
            Listeners\Api\Family\AddUserToFamilyGroup::class,
            Listeners\Api\Family\CreateFamilyForOfw::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
