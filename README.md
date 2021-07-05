# Authenticate as anyone

[comment]: <> ([![Packagist License]&#40;https://poser.pugx.org/barryvdh/laravel-debugbar/license.png&#41;]&#40;http://choosealicense.com/licenses/mit/&#41;)

[comment]: <> ([![Latest Stable Version]&#40;https://poser.pugx.org/barryvdh/laravel-debugbar/version.png&#41;]&#40;https://packagist.org/packages/akawam/authenticate-as-anyone&#41;)

[comment]: <> ([![Total Downloads]&#40;https://img.shields.io/packagist/dt/vendor_slug/package_slug.svg?style=flat-square&#41;]&#40;https://packagist.org/packages/vendor_slug/package_slug&#41;)


The purpose of this package is to allow restricted users to imitate another user account.

It is usefull when you want to see what exactly is happening on a certain user account.

## Installation

You can install the package via composer:

```bash
composer require akawam/authenticate-as-anyone
```

You can publish the view files with:

```bash
php artisan vendor:publish --provider="Akawam\AuthenticateAsAnyone\AuthenticateAsAnyoneServiceProvider" --tag="views"
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Akawam\AuthenticateAsAnyone\AuthenticateAsAnyoneServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    'route-prefix' => 'authenticate-as-anyone', //required
    'middlewares' =>
        [
            'auth', //optional
        ],
    'models' =>
        [
            //required name of the Model
            'User' =>
                [
                    'namespace' => 'App\Models',//optional (default is App\Models)
                    'columns' => [
                        'name' => 'name', //optional (default is name)
                        'firstname' => 'firstname', //optional (default is firstname)
                        'login' => 'email',//optional (default is email)
                    ],
                ],
        ],
];
```

## Usage

- Include the "logged as" ribbon

```html
...
<body>
@aaaLogged

...
</body>
</html>
```

- Access module through url/route : http://yourdomain.com/authenticate-as-anyone
- Imitate a user

## Adding your own event

When connecting as a user, an event is triggered (UserIsSwitching)

If you want to add some magic to your authentication (like adding some sessions data) you can do so by adding your own
event listener like so : 

```php
// 'app/Providers/EventServiceProvider.php'
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserIsSwitching::class => [
            TestListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

```

## Credits

- [Akawam](https://github.com/akawam)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more informations.
