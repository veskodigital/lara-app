<?php

use Illuminate\Support\Facades\Route;
use VeskoDigital\LaraApp\Http\Controllers\LaraAPIController;
use VeskoDigital\LaraApp\Http\Controllers\LaraAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('auth/login', [LaraAuthController::class, 'login'])->name('auth.login');

Route::group(
    [
        'prefix' => 'api/app/v1',
        'middleware' => [
            'auth:sanctum', 
            \VeskoDigital\LaraApp\Http\Middleware\APIAuthenticate::class
        ],
    ], function() {
        Route::post('set-env', [LaraAPIController::class, 'postSetEnv'])->name('info.set-env');
        Route::get('info', [LaraAPIController::class, 'getInfo'])->name('info.show');
        Route::get('commands', [LaraAPIController::class, 'getCommands'])->name('commands.show');
        Route::get('routes', [LaraAPIController::class, 'getRoutes'])->name('routes.show');
        Route::get('tables', [LaraAPIController::class, 'getTables'])->name('tables.show');
        Route::get('logs', [LaraAPIController::class, 'getLogs'])->name('logs.show');
        Route::get('log/{fileName}', [LaraAPIController::class, 'showLogDetail'])->name('log.show');
        Route::post('command', [LaraAPIController::class, 'postCommmand'])->name('command');
        Route::post('table', [LaraAPIController::class, 'getTable'])->name('get.table');
        Route::get('users/querydays', [LaraAPIController::class, 'getUsersQueryByDays'])->name('users.byday');

        Route::group(
            [
                'prefix' => 'device'
            ], function() {
            Route::post('token', [LaraAPIController::class, 'storeToken'])->name('device.store');
            Route::post('notifications/update', [LaraAPIController::class, 'updateNotifications'])->name('notifications.update');
            Route::get('notifications', [LaraAPIController::class, 'showNotifications'])->name('notifications.show');
        });
    }
);
