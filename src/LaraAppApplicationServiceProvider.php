<?php

namespace WooSignal\LaraApp;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class LaraAppApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the LaraApp authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        LaraApp::auth(function ($request) {
            return app()->environment('local') ||
                   Gate::check('viewLaraApp', [$request->user()]);
        });
    }

    /**
     * Register the LaraApp gate.
     *
     * This gate determines who can access LaraApp in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewLaraApp', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
