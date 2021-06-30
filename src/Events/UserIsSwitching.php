<?php

namespace Akawam\AuthenticateAsAnyone\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserIsSwitching
{
    use Dispatchable, SerializesModels;

    public $originalUser;
    public $user;

    public function __construct($originalUser, $user)
    {
        $this->originalUser = $originalUser;
        $this->user = $user;
    }
}
