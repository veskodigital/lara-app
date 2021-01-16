<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Console\Command;
use WooSignal\LaravelFCM\Console\Traits\DetectsApplicationNamespace;
use WooSignal\LaravelFCM\Models\LaraAppUser;
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
    protected $description = 'Updates or creates a new user for LaraApp';

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
            return;
        }

        if (empty($password)) {
            $this->info('Password cannot be empty');
            return;
        }

        $defaultUser = LaraAppUser::where('email', '=', 'me@thelara.app')->first();
        if (!is_null($defaultUser)) {
            if ($defaultUser->is_active == 1) {
                if ($this->confirm('Would you like to remove me@thelara.app user?')) {
                    $defaultUser->update('is_active', 0);
                }
            }
        }

        $this->comment('Generating hash for password...');
        $hashPw = Hash::make($password);

        $hadUser = LaraAppUser::where('email', $email)->first();

        $updatePw = LaraAppUser::updateOrCreate([
            'email' => $email,
        ],
        [
          'email' => $email,
          'password' => $hashPw,
          'app_token' => bin2hex(openssl_random_pseudo_bytes(20))  
      ]);

        if (is_null($hadUser) && $updatePw) {
            $this->info("LaraApp created a new user.\nEmail: " . $email . "\nPassword: *******");
        } else if (!is_null($hadUser) && $updatePw) {
            $this->info("LaraApp updated user\nEmail: " . $email);
        } else {
            $this->info('Something went wrong, please try again.');
        }
    }
}
