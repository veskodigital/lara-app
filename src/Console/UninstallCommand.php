<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Console\Command;
use WooSignal\LaraApp\Console\Traits\DetectsApplicationNamespace;
use Illuminate\Support\Facades\Schema;

class UninstallCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraapp:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Uninstall LaraApp';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure?')) {
        	return;
        }

        $this->info('Uninstalling LaraApp');

        $this->call('migrate', ['--path' => 'vendor/woosignal/laravel-laraapp/src/database/migrations/uninstall']);

        // $arrTablesToRemove = [];
        // if (Schema::hasTable('la_app_requests')) {
        //     $arrTablesToRemove[] = 'la_app_requests';
        // }
        // if (Schema::hasTable('la_user_devices')) {
        //     $arrTablesToRemove[] = 'la_user_devices';
        // }
        // if (Schema::hasTable('la_users')) {
        //     $arrTablesToRemove[] = 'la_users';
        // }

        // if (empty($arrTablesToRemove)) {
        // 	return;
        // }

        // foreach ($arrTablesToRemove as $table) {
        // 	$this->comment("Removing the '{$table}' table");
        // 	Schema::dropIfExists($table);
        // }

        $this->comment('LaraApp is uninstalled');
    }
}
