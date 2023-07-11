<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Console\Command;
use WooSignal\LaraApp\Console\Traits\DetectsApplicationNamespace;
use WooSignal\LaraApp\Models\LaUser;
use Hash;

class UpdateUserCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraapp:updateuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update or create a new user for LaraApp';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->ask('Enter the new login email');
        $password = $this->secret('What is the new password?');

        if (empty($email)) {
            $this->info('Email cannot be empty');
            return 1;
        }

        if (empty($password)) {
            $this->info('Password cannot be empty');
            return 1;
        }

        $defaultUser = LaUser::where('email', 'me@thelara.app')
                                    ->where('is_active', 1)
                                    ->first();
        if (!is_null($defaultUser)) {
            if ($this->confirm('Would you like to remove me@thelara.app user?')) {
                $defaultUser->update('is_active', 0);
            }
        }

        $this->comment('Generating hash for password...');

        $userUpdateOrCreate = LaUser::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'password' => Hash::make($password)
            ]
        );

        if(!$userUpdateOrCreate->wasRecentlyCreated && $userUpdateOrCreate->wasChanged()) {
            $this->info("LaraApp updated user\nEmail: " . $email);
            return 0;
        }

        if($userUpdateOrCreate->wasRecentlyCreated) {
            $this->info("LaraApp created a new user.\nEmail: " . $email . "\nPassword: *******");
            return 0;
        }

        $this->info('Something went wrong, please try again.');
    }
}
