<?php

use Illuminate\Support\Facades\Route;
use VeskoDigital\LaraApp\Http\Controllers\LaraAppController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('link', [LaraAppController::class, 'index'])->name('link.index');