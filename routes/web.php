<?php

Route::get('{model?}', 'AuthenticateAsAnyoneController@index')->name('index');
Route::get('auth/{model}/{userId}', 'AuthenticateAsAnyoneController@auth')->name('auth');
Route::get('log-back/{model}/{userId}', 'AuthenticateAsAnyoneController@logBack')->name('log-back');
