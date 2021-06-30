<?php

namespace Akawam\AuthenticateAsAnyone\Listeners;

use Akawam\AuthenticateAsAnyone\Events\UserIsSwitching;

class HandleUserSession
{
    public function handle(UserIsSwitching $event)
    {
        dump("ici le premier event depuis le package laravel");
    }
}
