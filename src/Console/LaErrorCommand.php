<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use WooSignal\LaraApp\Models\LaUserDevice;

class LaErrorCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laraapp:errors {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends push notifications to users about an application error.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $message = $this->argument('message');

        if (is_null($message)) {
            $this->comment('Error: Malformed message...');
            return;
        }

        $this->comment('Sending push notification from LaraApp...');
        $key = config('laraapp.appkey', '');
        if (empty($key)) {
            $this->info("Your application's key is empty in the laraapp config\nPlease visit https://thelara.app to get a token.");
            return;
        }
        $allUsersDevices = LaUserDevice::where('push_token', '!=', '')->where('is_active', 1)->get();
        foreach ($allUsersDevices->unique('push_token') as $device) {
            $settings = json_decode($device->push_settings, true);
            if (isset($settings['errors']) && $settings['errors'] != false) {
                $device->pushNotification(["message" => $message], 'api/v1/errors');
            }
       }
       $this->info("LaraApp will send a notification to your registered devices.\nMessage: " . $message);
   }
}
