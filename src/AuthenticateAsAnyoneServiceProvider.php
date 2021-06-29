<?php

namespace Akawam\AuthenticateAsAnyone;

use Akawam\AuthenticateAsAnyone\Http\Controllers\AuthenticateAsAnyoneController;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AuthenticateAsAnyoneServiceProvider extends ServiceProvider
{
    public $viewPath = __DIR__.'/../resources/views';

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/auth-as-anyone.php', 'auth-as-anyone');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        if ($this->app->has('view')) {
            $this->loadViewsFrom($this->viewPath, 'authenticate-as-anyone');
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->app->make(AuthenticateAsAnyoneController::class);

        if ($this->app->runningInConsole()) {
            //publish config
            $configPath = __DIR__.'/../config/auth-as-anyone.php';
            if (function_exists('config_path')) {
                $publishPath = config_path('auth-as-anyone.php');
            } else {
                $publishPath = base_path('config/auth-as-anyone.php');
            }
            $this->publishes([$configPath => $publishPath], 'config');

            //publish views
            if (function_exists('resource_path')) {
                $publishPath = resource_path('views/vendor/auth-as-anyone');
            } else {
                $publishPath = base_path('resources/views/vendor/auth-as-anyone');
            }
            $this->publishes([$this->viewPath => $publishPath]);
        }
        Blade::directive('aaaLogged', function ()
        {
            return "<?php echo view('authenticate-as-anyone::logged-ribbon')->render(); ?>";
        });
    }
}
