<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

if (!function_exists('aaaGetAttributes')) {
    /**
     * @param $modelData
     * @return array
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    function aaaGetAttributes($modelData): array
    {
        return [
            'name' => $modelData['columns']['name'] ?? 'name',
            'firstname' => $modelData['columns']['firstname'] ?? 'firstname',
            'login' => $modelData['columns']['login'] ?? 'email',
        ];
    }
}

if (!function_exists('aaaAddAuthenticateAttributes')) {
    /**
     * @param $model
     * @param $attributes
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    function aaaAddAuthenticateAttributes($model, $attributes): void
    {
        [$name, $firstName, $login] =
            [
                $attributes['name'],
                $attributes['firstname'],
                $attributes['login'],
            ];

        $model->aaaId = $model->getAuthIdentifier();
        $model->aaaName = $model->$name;
        $model->aaaFirstName = $model->$firstName;
        $model->aaaLogin = $model->$login;
    }
}

if (!function_exists('aaaGetGuardFromUser')) {
    /**
     * @param $user
     * @return string|null
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    function aaaGetGuardFromUser($user): ?string
    {
        $userClass = get_class($user);
        foreach (config('auth.providers') as $providerName => $provider) {
            if ($userClass === $provider['model']) {
                foreach (config('auth.guards') as $guardName => $guard) {
                    if ($providerName === $guard['provider']) {
                        return $guardName;
                    }
                }
            }
        }
        return null;
    }
}

if (!function_exists('aaaGetCurrentUser')) {
    /**
     * @return Authenticatable|null
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    function aaaGetCurrentUser(): ?Authenticatable
    {
        foreach (config('auth.guards') as $guardName => $guard) {
            if (Auth::guard($guardName)->check()) {
                $user = Auth::guard($guardName)->user();
                if (!$user) {
                    return null;
                }
                return $user;
            }
        }
        return null;
    }
}
