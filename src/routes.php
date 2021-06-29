<?php

Route::namespace('Akawam\AuthenticateAsAnyone\Http\Controllers')
    ->prefix(config('auth-as-anyone.route-prefix'))
    ->middleware(array_merge(['web'], config('auth-as-anyone.middlewares')))
    ->as('authenticate-as-anyone.')
    ->group(function ()
    {
        Route::get('', 'AuthenticateAsAnyoneController@index')->name('index');
        Route::get('auth/{model}/{userId}', 'AuthenticateAsAnyoneController@auth')->name('auth');
        Route::get('log-back/{model}/{userId}', 'AuthenticateAsAnyoneController@logBack')->name('log-back');
    });
