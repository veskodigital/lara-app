<?php

namespace WooSignal\LaraApp;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Gate;

class LaraAppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerMigrations();

        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->offerPublishing();
        $this->registerCommands();
    }

     /**
     * Setup the resource publishing groups for LaraApp.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/stubs/LaraAppServiceProvider.stub' => app_path('Providers/LaraAppServiceProvider.php'),
            ], 'laraapp-provider');

            $this->publishes([
                __DIR__.'/../config/laraapp.php' => config_path('laraapp.php'),
            ], 'laraapp-config');
        }
    }

    /**
     * Register the LaraApp migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    /**
     * Register the LaraApp routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'namespace' => 'WooSignal\LaravelFCM\Http\Controllers', 
            'prefix' => config('laraapp.path', 'lara-app'),
            'domain' => config('laraapp.domain', null),
            'middleware' => config('laraapp.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        });
    }

     /**
     * Register the LaraApp Artisan commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class
            ]);
        } else {
            $this->commands([
                Console\LaNewUsersCommand::class,
                Console\LaErrorCommand::class
            ]);
        }
    }
}