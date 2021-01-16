<?php
use Illuminate\Support\Facades\Route;

Route::get('link', 'LaraAppController@index')->name('laraapp.link.index');

Route::group(['prefix' => 'auth'], function() {
    Route::post('login', 'LaraAuthController@login')->name('laraapp.auth.login');
});

Route::group(
    [
        'prefix' => 'api',
        'middleware' => WooSignal\LaraApp\Http\Middleware\APIAuthenticate::class
    ], function() {
        Route::get('info', 'LaraAPIController@getInfo')->name('laraapp.info.show');
        Route::get('commands', 'LaraAPIController@getCommands')->name('laraapp.commands.show');
        Route::get('routes', 'LaraAPIController@getRoutes')->name('laraapp.routes.show');
        Route::get('tables', 'LaraAPIController@getTables')->name('laraapp.tables.show');
        Route::get('logs', 'LaraAPIController@getLogs')->name('laraapp.logs.show');
        Route::get('log/{fileName}', 'LaraAPIController@showLogDetail')->name('laraapp.log.show');
        Route::get('command', 'LaraAPIController@postCommmand')->name('laraapp.command');
        Route::get('delete/logs', 'LaraAPIController@deleteAllLogs')->name('laraapp.logs.delete');
        Route::get('delete/log', 'LaraAPIController@deleteLog')->name('laraapp.log.delete');

        Route::get('chart/users', 'LaraAPIController@chartUsers')->name('laraapp.chart.users.shows');

        Route::group(
            [
                'prefix' => 'device'
            ], function() {
            Route::post('token', 'LaraAPIController@storeToken')->name('laraapp.device.store');
            Route::post('notifications/update', 'LaraAPIController@updateNotifications')->name('laraapp.notifications.update');
            Route::get('notifications', 'LaraAPIController@showNotifications')->name('laraapp.notifications.show');
        });
    }
);
