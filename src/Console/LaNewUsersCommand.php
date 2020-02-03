<?php

namespace WooSignal\LaraApp\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;
use WooSignal\LaraApp\Models\LaUserDevice;

class LaNewUsersCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "laraapp:usersnap {when}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends push notifications for the amount of users signed up.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $arrOptionVals = ['yesterday','weekly','monthly'];
        $when = $this->argument('when');

        if (!in_array($when, $arrOptionVals)) {
            $this->info("Specify the date range for usersnap.\nOptions: yesterday | weekly | monthly");
            return;
        }

        $users = config('laraapp.user', \App\User::class);
        $amountUsers = 0;

        if (is_null($users)) {
            $this->info('LaraApp found 0 users so it didn\'t send out any notification');
            return;
        }
        switch ($when) {
            case 'yesterday':
            $amountUsers = $this->countForYesterday($users);
            break;
            case 'weekly':
            $amountUsers = $this->countForWeekly($users);
            break;
            case 'monthly':
            $amountUsers = $this->countForMonthly($users);
            break;
            default:
            $amountUsers = 0;
            break;
        }
        
        if ($amountUsers != 0) {
            $key = config('laraapp.appkey', '');
            if (empty($key)) {
                $this->info("Your application's key is empty in the laraapp config\nPlease visit https://thelara.app to get a token.");
                return;
            }
            $allUsersDevices = LaUserDevice::where('push_token','!=', '')->where('is_active', 1)->get();
            $this->comment('Sending push notification from LaraApp...');
            foreach ($allUsersDevices->unique('push_token') as $device) {
                $settings = json_decode($device->push_settings, true);
                if (isset($settings['newUsers']) && $settings['newUsers'] != false) {
                    $device->pushNotification(['count' => $amountUsers, 'type' => $when], 'api/v1/usersnap');
                }
            }
            $this->info("LaraApp found $amountUsers new users signed up ($when).\nSending notifications...");
        } else {
            $this->info('LaraApp found 0 users so it didn\'t send out any notification');
        }
    }

    /**
     * Returns the count of users for yesterday
     *
     * @return int
     */
    private function countForYesterday($users)
    {
        $usersYesterday = $users::whereDate('created_at', '=', date('Y-m-d', strtotime('yesterday')));
        if (!is_null($usersYesterday)) {
            return $usersYesterday->count();
        }
        return 0;
    }

    /**
     * Returns the weekly count of users
     *
     * @return int
     */
    private function countForWeekly($users)
    {
        $from = date('Y-m-d', strtotime('this week monday'));
        $to = date('Y-m-d', strtotime('this week sunday'));
        $usersWeekly = $users::whereBetween('created_at', [$from, $to]);
        if (!is_null($usersWeekly)) {
            return $usersWeekly->count();
        }
        return 0;
    }

    /**
     * Returns the monthly count of users
     *
     * @return int
     */
    private function countForMonthly($users)
    {
        $from = date('Y-m-d', strtotime('first day of this month'));
        $to = date('Y-m-d', strtotime('last day of this month'));
        $usersMonthly = $users::whereBetween('created_at', [$from, $to]);
        if (!is_null($usersMonthly)) {
            return $usersMonthly->count();
        }
        return 0;
    }
}
