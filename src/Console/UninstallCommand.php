<?php

namespace VeskoDigital\LaraApp\Console;

use Illuminate\Console\Command;
use VeskoDigital\LaraApp\Console\Traits\DetectsApplicationNamespace;
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

        $this->call('migrate', ['--path' => 'vendor/veskodigital/laravel-laraapp/src/database/migrations/uninstall']);

        $this->comment('LaraApp is uninstalled');
    }
}
