<?php

namespace Akawam\AuthenticateAsAnyone\Http\Controllers;

use Akawam\AuthenticateAsAnyone\Events\UserIsSwitching;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticateAsAnyoneController extends Controller
{
    private $models;

    public function __construct()
    {
        $this->models = config('auth-as-anyone.models');
    }

    /**
     * @return Application|Factory|View
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    public function index()
    {
        $data = collect();

        foreach ($this->models as $modelName => $modelData) {
            $pathModel = $this->getModelNamespace($modelData).'\\'.$modelName;
            $instance = new $pathModel;
            $modelCollection = $instance->paginate(10);

            $attributes = $this->getAttributes($modelData);

            foreach ($modelCollection as $modelCollectionValue) {
                $this->addAuthenticateAttributes($modelCollectionValue, $attributes);
            }

            $data->push((object) [
                'models' => $modelCollection,
                'modelName' => $modelName,
                'prettyName' => $modelData['pretty-name'],
            ]);
        }

        return view('authenticate-as-anyone::index', compact('data'));
    }

    /**
     * @param $model
     * @param $userId
     * @return RedirectResponse
     * @author Valentin Estreguil <estreguil.valentin@gmail.com>
     */
    public function auth($model, $userId): RedirectResponse
    {
        $modelExploded = explode('\\', $model);

        $user = (new $model)->findOrFail($userId);

        event(new UserIsSwitching($this->getCurrentUser(), $user));
        dd('pok');
        /*//reconnect origin user
        if (session()->has('aaa.origin-user')) {
            session()->forget(
                [
                    'aaa.origin-user',
                    'aaa.user',
                ]
            );
        } else {
            $modelData = $this->models[array_pop($modelExploded)];
            $this->handleSessions($user, $modelData);
        }*/
        Auth::guard($this->getGuardFromUser($user))->login($user);

        return redirect()->route('dashboard');
    }

    /**
     * @param  array  $modelData
     * @return string
     * @author Valentin Estreguil <estreguil.valentin@gmail.com>
     */
    private function getModelNamespace(array $modelData): string
    {
        return $modelData['namespace'] ?? 'App\Models';
    }

    private function handleSessions($user, $modelData): void
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser === null) {
            abort(404);
        }

        $attributes = $this->getAttributes($modelData);
        $this->addAuthenticateAttributes($user, $attributes);

        session()->put('aaa.origin-user', $currentUser);
        session()->put('aaa.user', $user);
    }

    /**
     * @return Authenticatable|null
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function getCurrentUser(): ?Authenticatable
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

    /**
     * @param $modelData
     * @return array
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function getAttributes($modelData): array
    {
        return [
            'id' => $modelData['columns']['id'] ?? 'id',
            'name' => $modelData['columns']['name'] ?? 'name',
            'firstname' => $modelData['columns']['firstname'] ?? 'firstname',
            'login' => $modelData['columns']['login'] ?? 'email',
        ];
    }

    /**
     * @param $model
     * @param $attributes
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function addAuthenticateAttributes($model, $attributes): void
    {
        [$id, $name, $firstName, $login] =
            [
                $attributes['id'],
                $attributes['name'],
                $attributes['firstname'],
                $attributes['login'],
            ];

        $model->aaaId = $model->$id;
        $model->aaaName = $model->$name;
        $model->aaaFirstName = $model->$firstName;
        $model->aaaLogin = $model->$login;
    }

    /**
     * @param $user
     * @return string|null
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    private function getGuardFromUser($user): ?string
    {
        $userClass = get_class($user);
        foreach (config('auth.providers') as $providerName => $provider){
            if ($userClass === $provider['model']){
                foreach (config('auth.guards') as $guardName => $guard){
                    if ($providerName === $guard['provider']){
                        return $guardName;
                    }
                }
            }
        }
        return null;
    }
}
