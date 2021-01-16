<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => 'api',
        'middleware' => WooSignal\LaraApp\Http\Middleware\APIAuthenticate::class
    ], function() {
        Route::get('commands', 'LaraAPIController@getCommands')->name('laraapp.commands.show');

        Route::group(
            [
                'prefix' => 'device'
            ], function() {
            Route::post('token', 'LaraAPIController@storeToken')->name('laraapp.device.store');
        });
    }
);
