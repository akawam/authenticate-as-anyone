<?php

namespace Akawam\AuthenticateAsAnyone\Providers;

use Akawam\AuthenticateAsAnyone\Events\UserIsSwitching;
use Akawam\AuthenticateAsAnyone\Listeners\HandleUserSession;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserIsSwitching::class => [
            HandleUserSession::class,
        ],
    ];
}
