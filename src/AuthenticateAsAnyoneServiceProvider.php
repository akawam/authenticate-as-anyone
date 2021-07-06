<?php

namespace Akawam\AuthenticateAsAnyone;

use Akawam\AuthenticateAsAnyone\Http\Controllers\AuthenticateAsAnyoneController;
use Akawam\AuthenticateAsAnyone\Providers\EventServiceProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthenticateAsAnyoneServiceProvider extends ServiceProvider
{
    public $viewPath = __DIR__.'/../resources/views';
    public $configPath = __DIR__.'/../config/auth-as-anyone.php';

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath, 'auth-as-anyone');
        $this->registerRoutes();
        $this->app->register(EventServiceProvider::class);

    }


    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadViewsFrom($this->viewPath, 'authenticate-as-anyone');
        $this->app->make(AuthenticateAsAnyoneController::class);

        if ($this->app->runningInConsole()) {
            //publish config
            $this->publishes([
                $this->configPath => config_path('auth-as-anyone.php'),
            ],
                'config');
            //publish views
            $this->publishes([
                $this->viewPath => resource_path('views/vendor/authenticate-as-anyone'),
            ],
                'views');
        }

        Blade::directive('aaaLogged', function ()
        {
            return "<?php echo view('authenticate-as-anyone::logged-ribbon')->render(); ?>";
        });
    }

    /**
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function ()
        {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Route configuration
     *
     * @return array
     * @author Valentin Estreguil <valentin.estreguil@akawam.com>
     */
    protected function routeConfiguration(): array
    {
        return [
            'namespace' => 'Akawam\AuthenticateAsAnyone\Http\Controllers',
            'prefix' => config('auth-as-anyone.route-prefix'),
            'as' => 'authenticate-as-anyone.',
        ];
    }
}
