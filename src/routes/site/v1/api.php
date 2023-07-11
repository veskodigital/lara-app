<?php

use Illuminate\Support\Facades\Route;
use WooSignal\LaraApp\Http\Controllers\LaraAppSiteAPIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(
    [
        'prefix' => 'site/api/v1',
        'middleware' => [
            \WooSignal\LaraApp\Http\Middleware\SiteAuthenticate::class
        ],
    ], function() {
        Route::get('user-snap', [LaraAppSiteAPIController::class, 'getUserSnap'])->name('site.v1.user-snap');
    }
);
