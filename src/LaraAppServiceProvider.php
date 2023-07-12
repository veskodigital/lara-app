<?php

namespace VeskoDigital\LaraApp;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lara-app');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/lara-app'),
        ], 'views');

        if (config('laraapp.observer.observer_created_user', false) == true) {
            config('laraapp.user', \App\Models\User::class)::observe(new \VeskoDigital\LaraApp\Observers\UserObserver);
        }
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
     * Register the LaraApp resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laraapp');
    }

    /**
     * Register the LaraApp routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => config('laraapp.path', 'lara-app'),
            'domain' => config('laraapp.domain', null),
            'middleware' => config('laraapp.middleware'),
            'as' => 'laraapp.'
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });

        Route::group([
            'prefix' => 'lara-app',
            'as' => 'laraapp.'
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/site/v1/api.php');
            $this->loadRoutesFrom(__DIR__ . '/routes/app/v1/api.php');
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
                Console\InstallCommand::class,
                Console\UninstallCommand::class,
                Console\UpdateUserCommand::class
            ]);
        }
    }
}
