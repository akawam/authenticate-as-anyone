<?php

namespace Akawam\AuthenticateAsAnyone\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserIsSwitching
{
    use Dispatchable, SerializesModels;

    public $currentUser;
    public $newUser;

    public function __construct($currentUser, $newUser)
    {
        $this->currentUser = $currentUser;
        $this->newUser = $newUser;
    }
}
