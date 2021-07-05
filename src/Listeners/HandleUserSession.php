<?php

namespace Akawam\AuthenticateAsAnyone\Listeners;

use Akawam\AuthenticateAsAnyone\Events\UserIsSwitching;

class HandleUserSession
{
    public function handle(UserIsSwitching $event): void
    {
        //reconnect origin user
        if (session()->has('aaa.origin-user')) {
            $this->deleteSession();
        } else {
            $modelExploded = explode('\\', get_class($event->newUser));
            $modelData = config('auth-as-anyone.models')[array_pop($modelExploded)];
            $this->handleSessions($event->currentUser, $event->newUser, $modelData);
        }
    }

    /**
     * Create aaa session before switching users
     *
     * @param $currentUser
     * @param $newUser
     * @param $modelData
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function handleSessions($currentUser, $newUser, $modelData): void
    {
        $attributes = aaaGetAttributes($modelData);
        aaaAddAuthenticateAttributes($newUser, $attributes);
        session()->put('aaa.origin-user', $currentUser);
        session()->put('aaa.user', $newUser);
    }

    /**
     * Delete aaa session
     *
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function deleteSession(): void
    {
        session()->forget(
            [
                'aaa.origin-user',
                'aaa.user',
            ]
        );
    }

}
