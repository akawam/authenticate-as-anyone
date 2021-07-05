<?php

namespace Akawam\AuthenticateAsAnyone\Http\Controllers;

use Akawam\AuthenticateAsAnyone\Events\UserIsSwitching;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index(Request $request, $model = null)
    {
        $currentUser = aaaGetCurrentUser();
        if ($currentUser === null) {
            abort(404);
        }

        $models = $this->models;

        if (empty($model)) {
            $modelName = array_key_first($models);
            $modelData = array_shift($models);
        } else {
            $modelName = $model;
            $modelData = $models[$model];
        }

        //get attributes for model
        $attributes = aaaGetAttributes($modelData);

        $pathModel = $this->getModelNamespace($modelData).'\\'.$modelName;
        $instance = new $pathModel;
        //search users
        $userCollection = $instance->when(!empty($request->search),
            function (Builder $query) use ($attributes, $request)
            {
                return $query->where($attributes['name'], 'like', '%'.$request->search.'%')
                    ->orWhere($attributes['firstname'], 'like', '%'.$request->search.'%')
                    ->orWhere($attributes['login'], 'like', '%'.$request->search.'%');
            })
            ->when(get_class($instance) === get_class($currentUser),
                function (Builder $query) use ($instance, $currentUser)
                {
                    return $query->where($instance->getAuthIdentifierName(), '<>', $currentUser->getAuthIdentifier());
                })
            ->paginate(10);

        foreach ($userCollection as $key => $user) {
            aaaAddAuthenticateAttributes($user, $attributes);
        }

        return view('authenticate-as-anyone::index')
            ->with([
                'users' => $userCollection->appends($request->only('search')),
                'models' => $this->models,
                'currentModelName' => $modelName,
            ]);
    }

    /**
     * @param $model
     * @param $userId
     * @return RedirectResponse
     * @author Valentin Estreguil <estreguil.valentin@gmail.com>
     */
    public function auth($model, $userId): RedirectResponse
    {
        $user = (new $model)->findOrFail($userId);

        $currentUser = aaaGetCurrentUser();
        if ($currentUser === null) {
            abort(404);
        }

        event(new UserIsSwitching($currentUser, $user));

        Auth::guard(aaaGetGuardFromUser($currentUser))->logout();
        Auth::guard(aaaGetGuardFromUser($user))->login($user);

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


}
