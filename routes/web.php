<?php

Route::group([
    'middleware' => array_merge(['web'], config('auth-as-anyone.middlewares')),
], function ()
{
    Route::get('{model?}', 'AuthenticateAsAnyoneController@index')->name('index');
    Route::get('auth/{model}/{userId}', 'AuthenticateAsAnyoneController@auth')->name('auth');
});

Route::group(['middleware' => 'web'], function ()
{
    Route::get('log-back/{model}/{userId}', 'AuthenticateAsAnyoneController@logBack')->name('log-back');
});
