<?php

namespace WooSignal\LaraApp\Http\Services;

use Illuminate\Support\Facades\Http;
use WooSignal\LaraApp\Models\LaUserDevice;
use Log;

/**
 * Class LaUserDeviceService
 *
 * @package WooSignal\LaraApp\Http\Services\LaUserDeviceService
 */
class LaUserDeviceService
{

    /**
     * LaUserDeviceService constructor
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Send a push notification
     *
     * @param $data
     * 
     * @return void
     */
    public function send($data)
    {
        $token = config('laraapp.appkey', '');

        if (empty($token)) {
            return;
        }

        $response = Http::withToken($token)
                        ->post("https://thelara.app/api/site/v1/notification", $data);
    }

    /**
     * Create payload meta data for a push notification
     *
     * @param $laUserDevices
     * @param $payload
     * @param $notificationType
     * 
     * @return array
     */
    public function payloadMeta($laUserDevices, $payload, $notificationType)
    {
        return [
            'push_tokens' => $laUserDevices->pluck('push_token'),
            'payload' => $payload,
            'notification_type' => $notificationType
        ];
    }

    /**
     * Broadcast a push notification to all user devices
     *
     * @param string $notificationType
     * @param $data
     * 
     * @return mixed
     */
    public function broadcastNotification($notificationType, $data)
    {
        $laUserDevices = $this->getAllUserDevices($notificationType);
        if (empty($laUserDevices)) {
            return;
        }

        $data = $this->payloadMeta($laUserDevices, $data, $notificationType);

        return $this->send($data);
    }

    /**
     * Get all user devices
     *
     * @param $enabledPushSetting
     * 
     * @return mixed
     */
    public function getAllUserDevices($enabledPushSetting = null)
    {
        $userDevices = LaUserDevice::where('push_token','!=', '')
                                    ->where('is_active', 1);

        if (!empty($enabledPushSetting)) {
            $userDevices->whereJsonContains("push_settings->{$enabledPushSetting}", true);
        }

        return $userDevices->get()->unique('push_token');
    }

    /**
     * Get a snapshot of total users
     *
     * @param $when
     * 
     * @return array
     */
    public function sendUserSnap($when)
    {
        $userModel = config('laraapp.user', \App\Models\User::class);
        if (is_null($userModel)) {
            Log::debug('User class is not found in your laraapp config');
            return false;
        }

        $notificationType = "{$when}_user_created_count";
        $tokens = $this->getAllUserDevices($notificationType);
        $data['notification_type'] = $notificationType;
        $data['tokens'] = $tokens->pluck('push_token');
        $appName = config('app.name');

        switch ($when) {
            case 'yesterday':
            $amountUsers = $this->countForYesterday($userModel);
            $data['message'] = "[$appName] $amountUsers new users created yesterday";
            return $data;
            case 'weekly':
            $amountUsers = $this->countForWeekly($userModel);
            $data['message'] = "[$appName] $amountUsers new users created last week";
            return $data;
            case 'monthly':
            $amountUsers = $this->countForMonthly($userModel);
            $data['message'] = "[$appName] $amountUsers new users created last month";
            return $data;
            default:
            return [];
        }
    }

     /**
     * Returns the count of users for yesterday
     *
     * @param $user
     * 
     * @return string
     */
    private function countForYesterday($user)
    {
        $amount = $user::whereDate('created_at', '=', date('Y-m-d', strtotime('yesterday')))->count();
        return $this->humanReadableNumberFormat($amount);
    }

    /**
     * Returns the weekly count of users
     *
     * @param $user
     * 
     * @return string
     */
    private function countForWeekly($user)
    {
        $from = date('Y-m-d', strtotime('this week monday'));
        $to = date('Y-m-d', strtotime('this week sunday'));
        $amount = $user::whereBetween('created_at', [$from, $to])->count();
        return $this->humanReadableNumberFormat($amount);
    }

    /**
     * Returns the monthly count of users
     *
     * @param $user
     * 
     * @return string
     */
    private function countForMonthly($user)
    {
        $from = date('Y-m-d', strtotime('first day of this month'));
        $to = date('Y-m-d', strtotime('last day of this month'));
        $amount = $user::whereBetween('created_at', [$from, $to])->count();
        return $this->humanReadableNumberFormat($amount);
    }

    /**
     * Create a human readable number format
     * 
     * @param $digit
     *
     * @return string
     */
    private function humanReadableNumberFormat($digit)
    {
        if ($digit >= 1000000000) {
            return round($digit/ 1000000000, 1). 'G';
        }
        if ($digit >= 1000000) {
            return round($digit/ 1000000, 1).'M';
        }
        if ($digit >= 1000) {
            return round($digit/ 1000, 1). 'K';
        }
        return $digit;
    }
}
