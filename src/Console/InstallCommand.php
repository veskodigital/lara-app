<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Facades\Schema;
use WooSignal\LaraApp\Models\LaraAppUser;
use Hash;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraapp:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the LaraApp resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing LaraApp Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'laraapp-provider']);

        $this->comment('Publishing LaraApp Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'laraapp-config']);

        $this->registerLaraAppServiceProvider();

        $this->info('LaraApp scaffolding installed successfully.');

        $arrTablesMissing = [];
        if (!Schema::hasTable('la_app_users')) {
            $arrTablesMissing[] = 'la_app_users';
        }
        if (!Schema::hasTable('la_user_devices')) {
            $arrTablesMissing[] = 'la_user_devices';
        }
        if (!Schema::hasTable('la_app_requests')) {
            $arrTablesMissing[] = 'la_app_requests';
        }

        if (count($arrTablesMissing) > 0) {
            $this->comment('You are missing the tables ' . implode(",", $arrTablesMissing) . ' for LaraApp to work...');

            if ($this->confirm('Would you also like to run the migration now too?')) {
                $this->comment('Running LaraApp migration...');
                $this->call('migrate', ['--path' => 'vendor/woosignal/lara-app-dev/src/database/migrations']);

                if (Schema::hasTable('la_app_users')) {
                    $userDefault = LaraAppUser::where('email', '=', 'me@lara.app')->first();
                    if (is_null($userDefault)) {
                        $userDefault = LaraAppUser::create([
                            'email' => 'me@lara.app',
                            'password' => Hash::make('app123')
                        ]);
                        $this->info("LaraApp user added to la_app_users\n\nemail: me@lara.app\npassword: app123\n");
                    }
                }
            }
        }
    }

    /**
     * Register the LaraApp service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerLaraAppServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\LaraAppServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\LaraAppServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/LaraAppServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/LaraAppServiceProvider.php'))
        ));
    }
}
