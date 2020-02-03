<?php 
namespace WooSignal\LaraApp\Observers;

use Illuminate\Database\Eloquent\Model as Eloquent;
use WooSignal\LaraApp\Models\LaUserDevice;

class UserObserver 
{

    public function created(Eloquent $model)
    {
        if (config('laraapp.observer.newUsers', false) == true) {
            $this->pushNotificationToAdmin();
        }
    }

    private function pushNotificationToAdmin()
    {
        $userDevices = LaUserDevice::where('push_token', '!=', '')->where('is_active', 1)->get();
        foreach ($userDevices->unique('push_token') as $userDevice) {
            $userDevice->pushNotification(['message' => 'User joined'], 'api/v1/user/joined');
        }
    }

}