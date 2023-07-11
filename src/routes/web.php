<?php

use Illuminate\Support\Facades\Route;
use WooSignal\LaraApp\Http\Controllers\LaraAppController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('link', [LaraAppController::class, 'index'])->name('link.index');